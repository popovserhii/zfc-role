<?php

namespace Popov\ZfcRole;

return [

    'assetic_configuration' => require 'assets.config.php',

    'navigation' => require 'navigation.config.php',

    'controllers' => [
        'invokables' => [
            'roles' => 'Popov\Roles\Controller\RolesController',
        ],
    ],

    // middleware
    'actions' => [
        'role' => __NAMESPACE__ . '\Action'
    ],

    // mvc
    'view_manager' => [
        'template_map' => [
            'roles/partial/settings-content' => __DIR__ . '/../view/popov/role/partial/settings/content.phtml',
        ],
        /*'template_path_stack' => [
            __DIR__ . '/../view',
        ],*/
        'prefix_template_path_stack' => [
            'role::' => __DIR__ . '/../view/role',
        ],
    ],

    // middleware
    'templates' => [
        'map' => [
            'roles::settings-content' => __DIR__ . '/../view/popov/role/partial/settings/content.phtml',
        ],
        'paths' => [
            'admin-role' => [__DIR__ . '/../view/admin/role'],
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'role' => View\Helper\Factory\RoleFactory::class,
        ],
    ],
    'dependencies' => [
        'aliases' => [
            'RoleService' => Service\RoleService::class,
        ],
        'invokables' => [
            Service\RoleService::class => Service\RoleService::class,
        ],
    ],
    // Doctrine config
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src//Model'],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Model' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];