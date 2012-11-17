<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\util\Validator;
use lithium\security\Password;

/**
 * Placeholder for custom validators
 */
$customValidators = array();

/**
 * Ensure that entered value is unique in database
 * The available options are:
 *  `original` array Original values to enable update event. You don't need to set this option if
 * you use this validator just on create. But update need to know original values to enable partial
 * update.
 */
$customValidators['unique'] = function($value, $format, $options) {
	$options += array('original' => array());
	$find = $options['model']::first(array('conditions' => array(
		$options['field'] => $value
	)));
	switch ($options['events']) {
		case 'create':
			return !$find;
		case 'update':
			if (isset($options['original'][$options['field']])) {
				return $value === $options['original'][$options['field']];
			}
			return !$find;
		default:
			return false;
	}
};

/**
 * Confirm that this field is equal to field against we compare.
 * The available options are:
 *  `strategy` string (direct|password)
 * Direct will compare raw values against desired field
 * Password will use `Password::check()`
 *  `against` string If we doesn't setup this option validator will assume that field against we
 * compare have sam name without `compare_` prefix. Eg.:
 * 'confirm_email' will check against 'email' if we doesn't specify this option.
 */
$customValidators['confirm'] = function($value, $format, $options) {
	$options += array(
		'strategy' => 'direct',
		'against' => null
	);

	$against = ($options['against']) ? $options['against'] : substr($options['field'], 8);

	switch ($options['strategy']) {
		case 'direct':
			return $value === $options['values'][$against];
		case 'password':
			return Password::check($value, $options['values'][$against]);
		default:
			return false;
	}
};

/**
 * Initialize custom validators
 */
Validator::add($customValidators);

?>