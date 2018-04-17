<?php
namespace Stagem\ZfcRole;

return [
    'routes' => [
        'admin(.*)' => [
            '@role_css',
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
            ],
        ],
    ],
];