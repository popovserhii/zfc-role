<?php
namespace Popov\ZfcRole\View\Helper\Factory;

use Interop\Container\ContainerInterface;

class RoleFactory
{
    public function __invoke(ContainerInterface $container)
    {

        return new \Popov\ZfcRole\View\Helper\Role($container->get('RoleService'));
    }
}