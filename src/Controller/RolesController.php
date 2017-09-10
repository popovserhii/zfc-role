<?php
namespace Popov\ZfcRole\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
	Zend\View\Model\JsonModel,
	Popov\Agere\Filter\Translit,
	Popov\ZfcRole\Form\Roles as RolesForm,
	Popov\Permission\Controller\PermissionAccessController;

class RolesController extends AbstractActionController {

	public $serviceName = 'RolesService';
	public $controllerRedirect = 'roles';
	public $actionRedirect = 'index';


	public function indexAction()
	{
		$locator = $this->getServiceLocator();
		/** @var \Popov\ZfcRole\Service\RoleService $service */
		$service = $locator->get($this->serviceName);

		$this->layout('layout/home');

		return [
			'items'		=> $service->getItemsCollection(),
		];
	}

	public function addAction()
	{
		$this->layout('layout/home');

		$viewModel = new ViewModel();
		$viewModel->setVariables($this->editAction());
		return $viewModel->setTemplate("magere/roles/edit.phtml");
	}

	public function editAction() {
		/** @var \Zend\Http\Request $request */
		$request = $this->getRequest();
		$route = $this->getEvent()->getRouteMatch();
		$sm = $this->getServiceLocator();
		/** @var \Popov\ZfcRole\Service\RoleService $service */
		$service = $sm->get($this->serviceName);

		$id = (int) $route->getParam('id');
		$item = $service->getOneItem($id);
		$form = new RolesForm();
		$fields = ['role', 'resource'];
		foreach ($fields as $field) {
			$method = 'get' . ucfirst($field);
			$form->get($field)->setValue($route->getParam($field, $item->$method()));
		}

		$permissionAccessController = new PermissionAccessController();
		if ($request->isPost()) {
			$post = $request->getPost()->toArray();
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
						$saveData['mnemo'] = str_replace(' ', '_', strtolower($translit->filter($saveData['role'])));
						$saveData['remove'] = '1';
					}

					$saveData['id'] = $id;
					$item = $service->save($saveData, $item);
					$permissionAccessController->edit($sm, $request, $item->getId());
				}
				$this->redirect()->toRoute('default', [
					'controller' => $this->controllerRedirect,
					'action'     => $this->actionRedirect,
				]);
			}
		}

		$this->layout('layout/home');
		/** @var \Popov\Menu\Service\MenuService $menuService */
		$menuService = $sm->get('MenuService');

		return [
			'id'    => $item->getId(),
			'form'  => $form,
			'tabs'  => $menuService->getItemsCollection(['level' => 0]),
			'items' => $permissionAccessController->edit($sm, null, $item->getId()),
		];
	}

	//------------------------------------AJAX----------------------------------------
	/**
	 * Ajax delete
	 */
	public function deleteAction()
	{
		/** @var \Zend\Http\Request $request */
		$request = $this->getRequest();

		if ($request->isPost() && $request->isXmlHttpRequest())
		{
			$locator = $this->getServiceLocator();
			/** @var \Popov\ZfcRole\Service\RoleService $service */
			$service = $locator->get($this->serviceName);

			// Access to page for current user
			$responseEvent = $service->delete(__CLASS__, []);
			$message = $responseEvent->first()['message'];
			// END Access to page for current user

			if ($message == '')
			{
				$allow = false;
				$post = $request->getPost();

				$allow = $service->deleteItem($post['id']);

				$result = new JsonModel([
					'message' => ($allow) ? '' : 'Невозможно удалить № '.$post['id'].'. Сначала уберите прив\'язку к позиции!',
				]);
			}
			else
			{
				$result = new JsonModel([
					'message' => $message,
				]);
			}

			return $result;
		}
		else
			$this->getResponse()->setStatusCode(404);
	}

}