<?php
namespace Popov\ZfcRole\View\Helper\Factory;

use Interop\Container\ContainerInterface;

class RoleFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $sm = $container->getServiceLocator();

        return new \Popov\ZfcRole\View\Helper\Role($sm->get('RoleService'));
    }
}