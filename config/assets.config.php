<?php
namespace Stagem\ZfcRole;

return [
    /*'routes' => [
        'admin(.*)' => [
            '@role_css',
            '@role_js',
        ],
    ],*/

    'controllers' => [
        'role' => [
            '@role_css',
            '@role_js',
        ],
    ],

    'modules' => [
        __NAMESPACE__ => [
            'root_path' => __DIR__ . '/../view/assets',
            'collections' => [
                'role_css' => [
                    'assets' => [
                        'css/custom-style.css',
                    ],
                    'options' => ['output' => 'role.css'],
                ],
                'role_js' => [
                    'assets' => [
                        'js/role.js',
                    ],
                ],
            ],
        ],
    ],
];