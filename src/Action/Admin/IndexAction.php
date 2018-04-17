<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2018 Serhii Popov
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Popov
 * @package Popov_ZfcRole
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\ZfcRole\Action\Admin;

use Popov\ZfcRole\Service\RoleService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Fig\Http\Message\RequestMethodInterface;
use Zend\View\Model\ViewModel;
use Popov\ZfcCurrent\CurrentHelper;
use Popov\ZfcRole\Block\Grid\RoleGrid;
use Popov\ZfcUser\Service\UserService;

class IndexAction implements MiddlewareInterface, RequestMethodInterface
{
    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * @var CurrentHelper
     */
    protected $currentHelper;

    /**
     * @var RoleGrid
     */
    protected $roleGrid;

    protected $config;

    public function __construct(RoleService $roleService, RoleGrid $roleGrid, CurrentHelper $currentHelper)
    {
        $this->roleService = $roleService;
        $this->roleGrid = $roleGrid;
        $this->currentHelper = $currentHelper;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $roles = $this->roleService->getRoles();

        $this->roleGrid->init();
        $productDataGrid = $this->roleGrid->getDataGrid();
        //$productDataGrid->setUrl($this->url()->fromRoute($route->getMatchedRouteName(), $url));
        $productDataGrid->setDataSource($roles);
        $productDataGrid->render();
        $response = $productDataGrid->getResponse();

        return $handler->handle($request->withAttribute(ViewModel::class, $response));
    }
}

