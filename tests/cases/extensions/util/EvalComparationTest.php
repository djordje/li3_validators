<?php

namespace li3_validators\tests\cases\extensions\util;

use li3_validators\extensions\util\EvalComparation;

class EvalComparationTest extends \lithium\test\Unit {

	public function testBuild() {
		$options = array(
			'conditions' => array(array('test_field', '>', 50)),
			'values' => array('test_field' => 75)
		);
		$expected = "return ((75 > 50));";
		$this->assertEqual($expected, EvalComparation::build($options));

		$options = array(
			'conditions' => array(
				array('test_field', '>', 50), '||', array('test_field', '==', 31)
			),
			'values' => array('test_field' => 75)
		);
		$expected = "return ((75 > 50) || (75 == 31));";
		$this->assertEqual($expected, EvalComparation::build($options));

		$options = array(
			'conditions' => array(
				array('test_field', '==', true), '&&', array('test_field_2', '===', null)
			),
			'values' => array('test_field' => 'test', 'test_field_2' => 'test_2')
		);
		$expected = "return (('test' == true) && ('test_2' === null));";
		$this->assertEqual($expected, EvalComparation::build($options));

		$options = array(
			'conditions' => array(
				array('test_field', '==', true), '&&',
				array(array('test_field_2', '===', null), '||', array('test_field_2', '===', false))
			),
			'values' => array('test_field' => 'test', 'test_field_2' => 'test_2')
		);
		$expected = "return (('test' == true) && (('test_2' === null) || ('test_2' === false)));";
		$this->assertEqual($expected, EvalComparation::build($options));
	}

}

?>