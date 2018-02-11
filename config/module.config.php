<?php
namespace Popov\ZfcRole;

return array(
	'controllers' => array(
		'invokables' => array(
			'roles' => 'Popov\Roles\Controller\RolesController'
		),
	),

	'view_manager' => array(
        'template_map' => array(
            'roles/partial/settings-content'    => __DIR__ . '/../view/popov/role/partial/settings/content.phtml',
        ),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),

    'view_helpers' => [
        'factories' => [
            'role' => View\Helper\Factory\RoleFactory::class,
        ],
    ],

    'dependencies' => array(
		'aliases' => array(
			'RoleService'	=> Service\RoleService::class,
		),
        'invokables' => array(
            Service\RoleService::class => Service\RoleService::class
        ),
	),

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
    'navigation' => array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            // And finally, here is where we define our page hierarchy
            'roles' => array(
                'module' => 'roles',
                'label' => 'Главная',
                'route' => 'default',
                'controller' => 'index',
                'action' => 'index',
                'pages' => array(
                    'settings-index' => array(
                        'label' => 'Настройки',
                        'route' => 'default',
                        'controller' => 'settings',
                        'action' => 'index',
                        'pages' => array(
                            'roles-index' => array(
                                'label' => 'Роли',
                                'route' => 'default',
                                'controller' => 'roles',
                                'action' => 'index',
                                'pages' => array(
                                    'roles-add' => array(
                                        'label' => 'Добавить',
                                        'route' => 'default',
                                        'controller' => 'roles',
                                        'action' => 'add',
                                    ),
                                    'roles-edit' => array(
                                        'label' => 'Редактировать',
                                        'route' => 'default/id',
                                        'controller' => 'roles',
                                        'action' => 'edit',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

);