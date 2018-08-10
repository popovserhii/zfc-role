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
 * @package Popov_ZfcUser
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\ZfcRole\Block\Grid;

use Popov\ZfcDataGrid\Block\AbstractGrid;
use Popov\ZfcRole\Model\Role;

class RoleGrid extends AbstractGrid
{
    //protected $createButtonTitle = '';
    protected $backButtonTitle = '';

    protected $id = Role::MNEMO;

    public function init()
    {
        $grid = $this->getDataGrid();
        $grid->setId('role');
        $grid->setTitle('Roles');
        //$grid->setRendererName('jqGrid');

        $rendererOptions = $grid->getToolbarTemplateVariables();

        //$rendererOptions['gridFooterRow'] = true;
        $rendererOptions['navGridDel'] = false;
        //$rendererOptions['navGridSearch'] = false;
        //$rendererOptions['inlineNavEdit'] = true;
        //$rendererOptions['inlineNavAdd'] = true;
        $rendererOptions['inlineNavCancel'] = false;
        $rendererOptions['navGridRefresh'] = true;

        $grid->setToolbarTemplateVariables($rendererOptions);

        $this->add([
            'name' => 'Select',
            'construct' => ['id', $this->id],
            'identity' => true,
        ])->getDataGrid()->getColumnByUniqueId('role_id');

        $this->add([
            'name' => 'Select',
            'construct' => ['name', $this->id],
            'label' => 'Role',
            'identity' => false,
            'width' => 3,
        ]);

        $this->add([
            'name' => 'Select',
            'construct' => ['mnemo', $this->id],
            'label' => 'Mnemo',
            'identity' => false,
            'width' => 3,
        ]);

        $this->add([
            'name' => 'Action',
            'construct' => ['edit'],
            'label' => ' ',
            'width' => 1,
            'styles' => [[
                'name' => 'BackgroundColor',
                'construct' => [[224, 226, 229]],
            ]],
            'formatters' => [[
                'name' => 'Link',
                'attributes' => ['class' => 'pencil-edit-icon', 'target' => '_blank'],
                'link' => ['href' => '/admin/role/edit/%s', 'placeholder_column' => [Role::MNEMO . '_id']]
            ]],
        ]);

        return $this;
    }
}