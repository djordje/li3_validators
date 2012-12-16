<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_validators\extensions\util;

/**
 * Build eval string that can be easily used (evaluated) in validators
 */
class EvalComparation {

	/**
	 * Example:
	 * {{{
	 * 	$options = array(
	 *      'conditions' => array(array('name', '===', 'diff_test_name')),
	 *      'values' => array('name' => 'test_name')
	 * 	);
	 * }}}
	 * `$eval = EvalComparation::build($options);` will generate:
	 * `return (('test_name' === 'diff_test_name'));` and that evaluate `false`
	 *
	 * {{{
	 * 	$options = array(
	 *      'conditions' => array(
	 *          array('name', '===', 'diff_test_name'), '||', array('name', '===', 'test_name')
	 *      ),
	 *      'values' => array('name' => 'test_name')
	 * 	);
	 * }}}
	 * `$eval = EvalComparation::build($options);` will generate:
	 * `return (('test_name' === 'diff_test_name') || ('test_name' === 'test_name'));`
	 * and that evaluate `true`
	 *
	 * @param array $options Accept `'conditions'` and `'values'` arrays
	 * 	`conditions` Array of conditons for generating eval string
	 * 	`values` Array of values that'll be used for comparation in generated eval string
	 * @return string
	 */
	public static function build(array $options = array()) {
		$options += array('conditions' => array(), 'values' => array());
		extract($options);
		$prepareValue = function($value) {
			if (is_string($value)) {
				return "'{$value}'";
			} elseif (is_null($value)) {
				return 'null';
			} elseif ($value === false) {
				return 'false';
			} elseif ($value === true) {
				return 'true';
			}
			return $value;
		};
		$builder = function($conditions) use($values, $prepareValue,  &$builder) {
			$comparation = '';
			foreach($conditions as $condition) {
				if (is_array($condition)) {
					if (is_array($condition[0])) {
						$comparation .= "({$builder($condition)})";
					} else {
						$comparation .= "({$prepareValue($values[$condition[0]])} {$condition[1]} "
							         .  "{$prepareValue($condition[2])})";
					}
				} elseif (is_string($condition)) {
					$comparation .= " {$condition} ";
				}
			}
			return $comparation;
		};
		return "return ({$builder($conditions)});";
	}

}

?>