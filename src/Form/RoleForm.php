<?php
namespace Popov\ZfcRole\Form;

use Zend\Form\Form,
	Zend\InputFilter\Factory as InputFactory,
	Zend\InputFilter\InputFilter;

class RoleForm extends Form {

	public function __construct()
	{
		parent::__construct('roles');

		$this->setAttribute('method', 'post');


		$this->add([
			'name' => 'name',
			'attributes' => [
				'required' => 'required'
			],
		]);
		$this->add(['name' => 'resource']);


		// filters
		$inputFilter = new InputFilter();
		$factory = new InputFactory();

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'name',
			'required' => true,
			'filters' => array(
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max' => 50
					)
				),
			)
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'resource',
			'required' => true,
		)));


		$this->setInputFilter($inputFilter);
	}

}