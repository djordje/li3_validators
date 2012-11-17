<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * Include validators that override some of Lithium's default validators
 */
require dirname(__DIR__) . '/validators/overridden.php';

/**
 * Include validators that doesn't affect Lithium's default validators
 */
require dirname(__DIR__) . '/validators/custom.php';

?>