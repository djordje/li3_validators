<?php

namespace li3_validators\tests\cases\validators;

use lithium\security\Password;
use lithium\util\Validator;
use li3_validators\tests\mocks\MockUser;

class CustomTest extends \lithium\test\Unit {

	public function testUnique() {
		$user = MockUser::create(array('username' => 'user2'));
		$this->assertFalse($user->validates());

		$user->username = 'user4';
		$this->assertTrue($user->validates());

		$user = MockUser::create(
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
	}

}

?>