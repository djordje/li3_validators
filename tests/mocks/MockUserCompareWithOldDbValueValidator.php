<?php

namespace li3_validators\tests\mocks;

class MockUserCompareWithOldDbValueValidator extends MockUser {

	public $validates = array(
		'old'=> array(
			array(
				'compareWithOldDbValue', 'message' => 'You must enter correct old username!',
				'field' => 'username', 'on' => 'test_old_name'
			),
			array(
				'compareWithOldDbValue', 'message' => 'You must enter correct old password!',
				'field' => 'password', 'on' => 'test_old_password', 'strategy' => 'password'
			)
		)
	);

}

?>