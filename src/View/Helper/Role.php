<?php
namespace Popov\ZfcRole\View\Helper;

use Zend\View\Helper\AbstractHelper,
	Popov\ZfcRole\Service\RoleService;

class Role extends AbstractHelper
{
	/**
	 * @var \Popov\ZfcRole\Service\RoleService
	 */
	protected $_rolesService;


	/**
	 * @param RoleService $rolesService
	 */
	public function __construct(RoleService $rolesService) {
		$this->_rolesService = $rolesService;
	}

	/**
	 * @param int|array $valSelected
	 * @param string $title
	 * @return string
	 */
	public function rolesList($valSelected, $title = '')
	{
		$strOptions = '<option value="">'.$title.'</option>';

		$collections = $this->_rolesService->getItemsCollection();

		foreach ($collections as $collection)
		{
			$selected = ((! is_array($valSelected) && $collection->getId() == $valSelected) OR (is_array($valSelected) && in_array($collection->getId(), $valSelected))) ? ' selected=""' : '';
			$strOptions .= '<option value="'.$collection->getId().'"'.$selected.'>'.$collection->getRole().'</option>';
		}

		return $strOptions;
	}

	/**
	 * @param int $valSelected
	 * @return string
	 */
	public function resourceList($valSelected)
	{
		$strOptions = '';

		$items = $this->_rolesService->getResources();

		foreach ($items as $key => $val)
		{
			$selected = ($key == $valSelected) ? ' selected=""' : '';
			$strOptions .= '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
		}

		return $strOptions;
	}

}