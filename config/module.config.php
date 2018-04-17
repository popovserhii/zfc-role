<?php

namespace Popov\ZfcRole;

return [
    'assetic_configuration' => require 'assets.config.php',

    'controllers' => [
        'invokables' => [
            'roles' => 'Popov\Roles\Controller\RolesController',
        ],
    ],

    // middleware
    'actions' => [
        'role' => __NAMESPACE__
    ],

    'view_manager' => [
        'template_map' => [
            'roles/partial/settings-content' => __DIR__ . '/../view/popov/role/partial/settings/content.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
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
    // @link http://adam.lundrigan.ca/2012/07/quick-and-dirty-zf2-zend-navigation/
    // All navigation-related configuration is collected in the 'navigation' key
    'navigation' => [
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => [
            // And finally, here is where we define our page hierarchy
            'roles' => [
                'module' => 'roles',
                'label' => 'Главная',
                'route' => 'default',
                'controller' => 'index',
                'action' => 'index',
                'pages' => [
                    'settings-index' => [
                        'label' => 'Настройки',
                        'route' => 'default',
                        'controller' => 'settings',
                        'action' => 'index',
                        'pages' => [
                            'roles-index' => [
                                'label' => 'Роли',
                                'route' => 'default',
                                'controller' => 'roles',
                                'action' => 'index',
                                'pages' => [
                                    'roles-add' => [
                                        'label' => 'Добавить',
                                        'route' => 'default',
                                        'controller' => 'roles',
                                        'action' => 'add',
                                    ],
                                    'roles-edit' => [
                                        'label' => 'Редактировать',
                                        'route' => 'default/id',
                                        'controller' => 'roles',
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];