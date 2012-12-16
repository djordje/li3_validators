<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\util\Validator;
use lithium\security\Password;
use li3_validators\extensions\util\EvalComparation;

/**
 * Placeholder for custom validators
 */
$customValidators = array();

/**
 * Ensure that entered value is unique in database
 * The available options are:
 *  `key` string Database table key
 *  `keyValue` string Setup  key value if you don't want to fetch it from 'values'
 */
$customValidators['unique'] = function($value, $format, $options) {
	$options += array('key' => 'id', 'keyValue' => null);
	$conditions = array(
		$options['field'] => $value
	);
	if ($options['events'] === 'update') {
		$key = $options['key'];
		$keyValue = ($options['keyValue']) ? $options['keyValue'] : $options['values'][$key];
		$conditions[$key] = array('!=' => $keyValue);
	}
	return !(boolean) $options['model']::first(array('conditions' => $conditions));
};

/**
 * Confirm that this field is equal to field against we compare.
 * The available options are:
 *  `strategy` string (direct|password)
 * Direct will compare raw values against desired field
 * Password will use `Password::check()`
 *  `against` string If we doesn't setup this option validator will assume that field against we
 * compare have sam name without `confirm_` prefix. Eg.:
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
 * Check field dependencies
 * @see \li3_validators\extensions\util\EvalComparation::build()
 */
$customValidators['dependencies'] = function($value, $format, $options) {
	$options += array('conditions' => array());
	return eval(EvalComparation::build($options));
};

/**
 * Compare value with existing value in database
 * The available options are:
 *  `strategy` string (direct|password)
 *  `findBy` string Field name that will be used as condition for finding original value
 *  `field` string Original field name
 */
$customValidators['compareWithOldDbValue'] = function($value, $format, $options) {
	$options += array('field' => '', 'findBy' => 'id', 'strategy' => 'direct');
	if ($options['field'] && $options['values'][$options['findBy']]) {
		$data = $options['model']::first(array(
			'conditions' => array($options['findBy'] => $options['values'][$options['findBy']]),
			'fields' => $options['field']
		));
		if ($data) {
			switch ($options['strategy']) {
				case 'direct':
					return $value === $data->{$options['field']};
				case 'password':
					return Password::check($value, $data->{$options['field']});
				default:
					return false;
			}
		}
	}
	return false;
};

/**
 * Initialize custom validators
 */
Validator::add($customValidators);

?>