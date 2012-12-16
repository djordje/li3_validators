<?php

namespace li3_validators\tests\mocks;

class MockUserUniqueValidator extends MockUser {

	public $validates = array(
		'username' => array('unique', 'message' => 'Username must be unique!')
	);

}

?>