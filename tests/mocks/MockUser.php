<?php

namespace li3_validators\tests\mocks;

use lithium\data\entity\Record;
use lithium\security\Password;

class MockUser extends \lithium\tests\mocks\data\MockBase {

	public static $connection = null;

	protected $_meta = array('connection' => false, 'key' => 'id');

	public static function instances() {
		return array_keys(static::$_instances);
	}

	public static function find($type = 'all', array $options = array()) {
		$result = array();
		$users = array(
			array(
				'id' => 1, 'username' => 'user1', 'email' => 'user1@example.com',
				'password' => Password::hash('user1')
			),
			array(
				'id' => 2, 'username' => 'user2', 'email' => 'user2@example.com',
				'password' => Password::hash('user2')
			),
			array(
				'id' => 3, 'username' => 'user3', 'email' => 'user3@example.com',
				'password' => Password::hash('user3')
			)
		);

		switch ($type) {
			case 'first':
				if ($options['conditions']) {
					$conditions = '';
					foreach ($options['conditions'] as $key => $condition) {
						!$conditions || $conditions .= ' && ';
						$comparation = '==';
						if (is_array($condition)) {
							$keys = array_keys($condition);
							$comparation = $keys[0];
							$condition = $condition[$keys[0]];
						}
						$conditions .= "\$user['{$key}'] {$comparation} '{$condition}'";
					}
					$eval = "return ({$conditions});";
					foreach ($users as $user) {
						if ((eval($eval))) {
							$result[] = $user;
						}
					}
					if ($result) {
						return new Record(array('data' => array($result[0]), 'model' => __CLASS__));
					}
					return null;
				}
				return new Record(array('data' => array($users[0]), 'model' => __CLASS__));
			case 'all':
			default:
				return new Record(array('data' => $users, 'model' => __CLASS__));
		}
	}

}

?>