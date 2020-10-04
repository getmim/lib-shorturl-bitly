<?php

return [
    '__name' => 'lib-shorturl-bitly',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/lib-shorturl-bitly.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'https://iqbalfn.com/'
    ],
    '__files' => [
        'modules/lib-shorturl-bitly' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'lib-shorturl' => NULL
            ],
            [
                'lib-cache' => NULL
            ],
            [
                'lib-curl' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'LibShorturlBitly\\Library' => [
                'type' => 'file',
                'base' => 'modules/lib-shorturl-bitly/library'
            ]
        ],
        'files' => []
    ],
    'libShortURL' => [
        'shorteners' => [
            'bit.ly' => 'LibShorturlBitly\\Library\\Shortener'
        ]
    ],
    'libShortURLBitly' => [
        'client' => [],
        'user' => [],
        'group' => []
    ],
    '__inject' => [
        [
            'name' => 'libShortURLBitly',
            'children' => [
                [
                    'name' => 'client',
                    'children' => [
                        [
                            'name' => 'id',
                            'question' => 'Api client id',
                            'rule' => '!^.+$!'
                        ],
                        [
                            'name' => 'secret',
                            'question' => 'Api client secret',
                            'rule' => '!^.+$!'
                        ]
                    ]
                ],
                [
                    'name' => 'user',
                    'children' => [
                        [
                            'name' => 'name',
                            'question' => 'Account user name',
                            'rule' => '!^.+$!'
                        ],
                        [
                            'name' => 'password',
                            'question' => 'Account user password',
                            'rule' => '!^.+$!'
                        ]
                    ]
                ],
                [
                    'name' => 'group',
                    'children' => [
                        [
                            'name' => 'guid',
                            'question' => 'Account group guid',
                            'rule' => '!^.+$!'
                        ]
                    ]
                ]
            ]
        ]
    ]
];