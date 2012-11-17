<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\util\Validator;

/**
 * Placeholder for custom validators
 */
$overriddenValidators = array();

/**
 * Check that value is valid email. The available options are
 *  `'mx'` boolean that enable validator to check if MX DNS record exists and
 *  `'pattern'` mixed `false` to use `filter_var()` function (default in lithium)
 * or regex to check against. By default this filter check against custom regex
 * that doesn't match all [RFC 5322](http://tools.ietf.org/html/rfc5322) valid
 * emails, but will match against most correct emails, and doesn't check domain
 * against MX DNS record. With combinations of this options you can achieve
 * enough validations, including lithium's default (`'mx' => false, 'pattern' => false` ).
 */
$overriddenValidators['email'] = function($value, $format, $options) {
	$defaults = array(
		'mx' => false,
		'pattern' => '/^[a-z0-9][a-z0-9_.-]*@[a-z0-9.-]{3,}\.[a-z]{2,4}$/i'
	);
	$options += $defaults;
	$valid = true;
	switch ($options['pattern']) {
		case false:
			$valid = filter_var($value, FILTER_VALIDATE_EMAIL);
			break;
		default:
			$valid = preg_match($options['pattern'], $value);
			break;
	}
	if ($valid && $options['mx'] && function_exists('checkdnsrr')) {
		$valid = checkdnsrr(end(explode('@', $value)), 'MX');
	}
	return $valid;
};

/**
 * Initialize overridden validators
 */
Validator::add($overriddenValidators);

?>