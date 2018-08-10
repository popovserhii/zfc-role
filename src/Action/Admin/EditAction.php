<?php

namespace Popov\ZfcRole\Action\Admin;

use Popov\ZfcCore\Helper\UrlHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

// @todo wait until they will start to use Pst in codebase @see https://github.com/zendframework/zend-mvc/blob/master/src/MiddlewareListener.php#L11
//use Psr\Http\Server\MiddlewareInterface;
//use Psr\Http\Server\RequestHandlerInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;

use Popov\ZfcCore\Filter\Translit;
use Popov\ZfcPermission\Controller\PermissionAccessController;
use Popov\ZfcPermission\Service\PermissionService;
use Popov\ZfcRole\Model\Role;
use Popov\ZfcRole\Service\RoleService;
use Fig\Http\Message\RequestMethodInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Router\RouteMatch;
use Zend\View\Model\ViewModel;
use Popov\ZfcForm\FormElementManager;
use Popov\ZfcUser\Form\UserForm;
use Popov\ZfcUser\Service\UserService;
use Popov\ZfcUser\Model\User;

use Popov\ZfcRole\Form\RoleForm;


class EditAction implements MiddlewareInterface, RequestMethodInterface
{
    /** @var UserService */
    protected $roleService;

    /** @var PermissionService */
    protected $permissionService;

    /** @var PermissionAccessController */
    protected $permissionAccessController;

    /** @var FormElementManager */
    protected $formManager;

    /** @var UrlHelper */
    protected $urlHelper;

    public function __construct(
        RoleService $roleService,
        PermissionService $permissionService,
        PermissionAccessController $permissionAccessController,
        FormElementManager $formManager,
        UrlHelper $urlHelper
    ) {
        $this->roleService = $roleService;
        $this->formManager = $formManager;
        $this->permissionService = $permissionService;
        $this->permissionAccessController = $permissionAccessController;
        $this->urlHelper = $urlHelper;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(RouteMatch::class);

        /** @var Role $user */
        $role = ($role = $this->roleService->find($id = (int) ($route->getParams()['id'] ?? 0)))
            ? $role
            : $this->roleService->getObjectModel();


        $form = new RoleForm();
        $fields = ['name', 'resource'];
        foreach ($fields as $field) {
            $method = 'get' . ucfirst($field);
            $form->get($field)->setValue($params[$field] ?? $role->$method());
        }


        if ($request->getMethod() == self::METHOD_POST) {
            $post = $request->getParsedBody();
            $form->setData($post);
            if ($form->isValid()) {
                $post = $form->getData();
                $saveData = [];
                foreach ($fields as $field) {
                    $saveData[$field] = $post[$field];
                }
                if ($saveData) {
                    if (!$id) {
                        $translit = new Translit();
                        $saveData['mnemo'] = str_replace(' ', '_', strtolower($translit->filter($saveData['name'])));
                        //$saveData['remove'] = '1';
                    }

                    $saveData['id'] = $id;
                    $item = $this->roleService->save($saveData, $role);

                    if (!$id) {
                        $this->permissionService->updateSettings(); // @todo exclude to event
                    }

                    $this->roleService->getObjectManager()->persist($item);
                    $this->roleService->getObjectManager()->flush();

                    $this->permissionAccessController->edit($request->getParsedBody(), $item->getId());
                }

                return new RedirectResponse($this->urlHelper->generate('admin/default', [
                    'controller' => 'role',
                    'action' => 'index',
                ]));
            }
        }

        $view = new ViewModel([
            'form'  => $form,
            'items' => $this->permissionAccessController->edit(null, $role->getId()),
        ]);

        return $handler->handle($request->withAttribute(ViewModel::class, $view));
    }

    public function processOld(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $flash = $request->getAttribute('flash');
        $route = $request->getAttribute(RouteResult::class);

        /** @var User $user */
        $user = ($user = $this->roleService->find($id = (int) ($route->getMatchedParams()['id'] ?? 0)))
            ? $user
            : $this->roleService->getObjectModel();

        /** @var UserForm $form */
        $form = $this->formManager->get(UserForm::class);
        $form->bind($user);
        if ($request->getMethod() == self::METHOD_POST) {
            $params = $request->getParsedBody();
            $form->setData($params);
            if ('' === ($password = $form->get('user')->get('password')->getValue())) {
                $form->getInputFilter()->get('user')->remove('password');
            }

            if ($form->isValid()) {
                if ($password) { // password is send by POST
                    $user->setPassword(UserService::getHashPassword($password));
                }

                $om = $this->roleService->getObjectManager();
                $om->persist($user);
                $om->flush();

                #$this->getEventManager()->trigger($route->getParam('action') . '.post', $user, ['password' => $password]);

                $flash->addMessage('User has been successfully saved', 'success');

                return new RedirectResponse($this->urlHelper->generate('admin/default', [
                    'controller' => 'user',
                    'action' => 'index',
                ]));
            } else {
                $flash->addMessage('Form is invalid. Please, check the correctness of the entered data', 'error');
            }
        }

        $view = new ViewModel([
            'form' => $form,
        ]);

        return $handler->handle($request->withAttribute(ViewModel::class, $view));
    }
}