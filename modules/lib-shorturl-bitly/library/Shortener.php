<?php
/**
 * Shortener
 * @package lib-shorturl-bitly
 * @version 0.0.1
 */

namespace LibShorturlBitly\Library;

use LibCurl\Library\Curl;

class Shortener
    implements 
        \LibShorturl\Iface\Shortener
{

    protected static $last_error;

    private static function getToken(): ?string{
        $cache_name = 'lib-shorturl-bitly-token';
        $token = \Mim::$app->cache->get($cache_name);
        if($token)
            return $token;

        $config = \Mim::$app->config->libShortURLBitly;
        $client = $config->client;
        $auth   = base64_encode($client->id.':'.$client->secret);
        $result = Curl::fetch([
            'url'   => 'https://api-ssl.bitly.com/oauth/access_token',
            'method'=> 'POST',
            'body'  => [
                'grant_type' => 'password',
                'username'   => $config->user->name,
                'password'   => $config->user->password
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $auth
            ]
        ]);

        if(!isset($result->access_token)){
            self::$last_error = $result->status_txt;
            return null;
        }

        $token   = $result->access_token;
        $expires = 60*60*24*30*12;
        \Mim::$app->cache->add($cache_name, $token, $expires);

        return $token;
    }

    static function lastError(): ?string {
        return self::$last_error;
    }

    static function shorten(string $url): ?string {
        $token = self::getToken();
        if(!$token)
            return null;

        $config = \Mim::$app->config->libShortURLBitly;
        $result = Curl::fetch([
            'url'   => 'https://api-ssl.bitly.com/v4/shorten',
            'method'=> 'POST',
            'body'  => [
                'group_guid' => $config->group->guid,
                'domain' => 'bit.ly',
                'long_url' => $url
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        if(isset($result->message)){
            self::$last_error = $result->message;
            return null;
        }

        return $result->link;
    }
}