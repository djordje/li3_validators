<?php

namespace li3_validators\tests\cases\validators;

use lithium\util\Validator;

class OverriddenTest extends \lithium\test\Unit {

	/**
	 * Options variations:
	 *   1. +pattern -mx (default)
	 *   2. +pattern +mx
	 *   3. -pattern -mx
	 *   4. -pattern +mx
	 *
	 * `$emails[0]` with any `$options` or without options should be `true`
	 * `$emails[1]` with any `$options` or without options should be `false`
	 * `$emails[2]` with `$options[1]` should be `true`
	 * `$emails[2]` with `$options[1]` should be `true`
	 *
	 * `$options[1]` works same as Lithium's default email validator implementation
	 */
	public function testEmail() {
		$emails = array(
			'li3test@djordjekovacevic.com',
			'invalid.djordjekovacevic.com',
			'looks.valid@djordjekovacevic.c'
		);

		$options = array(
			array('mx' => true),
			array('pattern' => false),
			array('pattern' => false, 'mx' => true)
		);

		$this->assertTrue(Validator::rule('email', $emails[0]));
		$this->assertTrue(Validator::rule('email', $emails[0], 'any', $options[0]));
		$this->assertTrue(Validator::rule('email', $emails[0], 'any', $options[1]));
		$this->assertTrue(Validator::rule('email', $emails[0], 'any', $options[2]));

		$this->assertFalse(Validator::rule('email', $emails[1]));
		$this->assertFalse(Validator::rule('email', $emails[1], 'any', $options[0]));
		$this->assertFalse(Validator::rule('email', $emails[1], 'any', $options[1]));
		$this->assertFalse(Validator::rule('email', $emails[1], 'any', $options[2]));

		$this->assertFalse(Validator::rule('email', $emails[2], 'any'));
		$this->assertTrue(Validator::rule('email', $emails[2], 'any', $options[1]));
	}

}

?>