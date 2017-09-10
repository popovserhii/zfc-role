<?php
namespace Popov\ZfcRole;

class Module
{


    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /*public function getViewHelperConfig()
    {
        return [
            'factories' => [
                'roles' => function ($sm) {
                    $locator = $sm->getServiceLocator();

                    return new \Popov\ZfcRole\View\Helper\Role($locator->get('RolesService'));
                },
            ],
        ];
    }*/
}