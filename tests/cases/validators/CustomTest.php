<?php

namespace li3_validators\tests\cases\validators;

use lithium\security\Password;
use lithium\util\Validator;
use li3_validators\tests\mocks\MockUserUniqueValidator;
use li3_validators\tests\mocks\MockUserCompareWithOldDbValueValidator;

class CustomTest extends \lithium\test\Unit {

	public function testUnique() {
		$user = MockUserUniqueValidator::create(array('username' => 'user2'));
		$this->assertFalse($user->validates());

		$user->username = 'user4';
		$this->assertTrue($user->validates());

		$user = MockUserUniqueValidator::create(
			array('id' => 2), array('exists' => true)
		);

		$user->username = 'user3';
		$this->assertFalse($user->validates());

		$user->username = 'user2';
		$this->assertTrue($user->validates());
	}

	public function testConfirm() {
		$validate = Validator::check(
			array(
				'password' => 'user5',
				'confirm_password' => 'user5'
			),
			array(
				'confirm_password' => array('confirm', 'message' => 'Please confirm your password!')
			)
		);
		$this->assertTrue(empty($validate));

		$validate = Validator::check(
			array(
				'password' => 'user5',
				'confirm_password' => 'user4'
			),
			array(
				'confirm_password' => array('confirm', 'message' => 'Please confirm your password!')
			)
		);
		$this->assertTrue(!empty($validate));

		$validate = Validator::check(
			array(
				'password' => Password::hash('user5'),
				'confirm_password' => 'user5'
			),
			array(
				'confirm_password' => array(
					'confirm', 'message' => 'Please confirm your password!', 'strategy' => 'password'
				)
			)
		);
		$this->assertTrue(empty($validate));

		$validate = Validator::check(
			array(
				'password' => Password::hash('user5'),
				'confirm_password' => 'user4'
			),
			array(
				'confirm_password' => array(
					'confirm', 'message' => 'Please confirm your password!', 'strategy' => 'password'
				)
			)
		);
		$this->assertTrue(!empty($validate));

		$validate = Validator::check(
			array(
				'original_password' => 'user5',
				'confirm_password' => 'user5'
			),
			array(
				'confirm_password' => array(
					'confirm', 'message' => 'Please confirm your password!', 'against' => 'original_password'
				)
			)
		);
		$this->assertTrue(empty($validate));

		$validate = Validator::check(
			array(
				'original_password' => 'user5',
				'confirm_password' => 'user4'
			),
			array(
				'confirm_password' => array(
					'confirm', 'message' => 'Please confirm your password!', 'against' => 'original_password'
				)
			)
		);
		$this->assertTrue(!empty($validate));
	}

	public function testDependencies() {
		$values = array('field_one' => 'some value', 'field_one_dep' => 'incorrect');
		$rules = array(
			'field_one' => array(
				'dependencies', 'message' => 'Dependencies not correct!',
				'conditions' => array(array('field_one_dep', '===', 'correct'))
			)
		);
		$validate = Validator::check($values, $rules);
		$this->assertTrue(!empty($validate));

		$values['field_one_dep'] = 'correct';
		$validate = Validator::check($values, $rules);
		$this->assertTrue(empty($validate));

		$values = array('name' => 'John Doe', 'gender' => 'M', 'height' => 198);
		$rules = array(
			'name' => array(
				'dependencies', 'message' => 'Dependencies not correct!',
				'conditions' => array(
					array('gender', '===', 'M'), '&&', array('height', '>', 195)
				)
			)
		);
		$validate = Validator::check($values, $rules);
		$this->assertTrue(empty($validate));

		$values['height'] = 195;
		$validate = Validator::check($values, $rules);
		$this->assertTrue(!empty($validate));

		$values = array('name' => 'John Doe', 'gender' => 'M', 'height' => 198);
		$rules = array(
			'name' => array('dependencies', 'message' => 'Dependencies not correct!')
		);
		$validate = Validator::check($values, $rules);
		$this->assertTrue(empty($validate));
	}

	public function testCompareWithOldDbValue() {
		$user = MockUserCompareWithOldDbValueValidator::create(array('old' => 'user2', 'id' =>2));

		$this->assertTrue($user->validates(array('events' => 'test_old_name')));

		$this->assertTrue($user->validates(array('events' => 'test_old_password')));
	}

	public function testConditionalInRange() {
		$values = array('height' => 195, 'gender' => 'M');
		$rules = array(
			'height' => array(
				array(
					'conditionalInRange', 'message' => 'Incorrect value for given condition!',
					'upper' => 201, 'lower' => 184,
					'conditions' => array(array('gender', '===', 'M'))
				),
				array(
					'conditionalInRange', 'message' => 'Incorrect value for given condition!',
					'upper' => 186, 'lower' => 167,
					'conditions' => array(array('gender', '===', 'W'))
				)
			)
		);
		$validate = Validator::check($values, $rules);
		$this->assertTrue(empty($validate));

		$values['gender'] = 'W';
		$validate = Validator::check($values, $rules);
		$this->assertTrue(!empty($validate));

		$values['height'] = 171;
		$validate = Validator::check($values, $rules);
		$this->assertTrue(empty($validate));

		$values['height'] = 165;
		$validate = Validator::check($values, $rules);
		$this->assertTrue(!empty($validate));
	}

}

?>