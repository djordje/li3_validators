# Collection of reusable validators for [Lithium PHP Framework](https://github.com/UnionOfRAD/lithium)

___

## Table of content:

* **[Instalation and usage](#installation-and-usage)**
* **[Custom validators:](#custom-validators)**
  * [Unique](#unique-validator)
  * [Confirm](#confirm-validator)
  * [Dependencies](#dependencies-validator)
  * [Compare with old db value](#compare-with-old-db-value-validator)
  * [Conditional in range](#conditional-in-range-validator)
* **[Overridden validators:](#overridden-validators)**
  * [Email](#email-validator)
* **[Eval comparation builder](#eval-comparation-builder)**
* **[Project status](#project-status)**

## Installation adn usage:

**Git clone:**

```
	cd path/to/libraries
	git clone git://github.com/djordje/li3_validators.git
```

**Or trough _composer_:**

Add this to yout `composer.json` file:

```
	{
		"minimum-stability": "dev",
		"require": {
			"djordje/li3_validators": "dev-master"
		}
	}
```

After either of these two steps open `app/config/bootstrap/libraries.php` with your editor and add
this to bootom of the file:

`Libraries::add('li3_validators')`

Now you can use this validators in your model just as any other bundled or added validator!

___



## Custom validators:

###### Unique validator

**Name:** `'unique'`

Ensure that entered value is unique in database. If `'events'` is *update* do query with provided options

**Options:**

`'key'` string - Database table key that will be used as condition key if `'events'` is update, by default `'id'`

`'keyValue'` string - Setup key value if you don't want to fetch it from `'values'` field, by default `null`
 which means that field fetch value of `$options['values']['id']` if you don't change `'key'`


###### Confirm validator

**Name:** `'confirm'`

Confirm that this field is equal to field against we compare.

**Options:**

`'strategy'` string (direct|password) - Default is `'direct'` which means we compare value against
desired field directly `'string' === 'string'`. If we set this to `'password'` validator use
`Password::check()` for comparing value against desired field

`'against'` string - By default this is `null` which means we will compare this field against same
named field with `confirm_` prefix, eg. `email` against `confirm_email`, or we can set field name
against we want to compare



###### Dependencies validator

**Name:** `'dependencies'`

Check field dependencies. Evaluate conditions to see if all dependencies are correct

**Options:**

`'conditions'` array - [Eval comparation builder](#eval-comparation-builder) compatible conditions array

Example:

```

	$options = array('conditions' => array(
		array('gender', '===', 'M')
	));

```

This field will require `'gender'` field equal to `'M'`



###### Compare with old db value validator

**Name:** `'compareWithOldDbValue'`

Compare value with existing value in database

**Options:**

`'strategy'` string (direct|password) - Default is `'direct'` which means we compare value against
desired field directly `'string' === 'string'`. If we set this to `'password'` validator use
`Password::check()` for comparing value against desired field

`'findBy'` string - Field name that will be used as condition for finding original value, default is `'id'`

`'field'` string - Original field name

Example:

```

	$options = array(
		'strategy' => 'password',
		'field' => 'password'
	);

```

This validator will assume that value of this field, for example `'old_password'` is equal to the
value of `'password'` field where `'id'` is equal to current `'id'`



###### Conditional in range validator

**Name:** `'condtionalInRange'`

This validator is very similar to Lithium's `'inRange'` validator, but require conditions to be `true`
as well

**Options:**

`'upper'` integer

`'lower'` integer

`'conditions'` array - [Eval comparation builder](#eval-comparation-builder) compatible conditions array

Example:

```

	$options = array(
		'lower' => 169, 'upper' => '206',
		'conditions' => array(
        	array('gender', '===', 'M')
        )
	);

```

This assume that value of this field (for example `'height'`) is greater than 169 and smaller than
206 just if `'gender'` field exists and is qual to `'M'`

___



## Overridden validators:

###### Email validator

**Name:** `'email'`

**Options:**

`'pattern'` mixed (false|regex) - If `false` use php `filter_var()` function (default in Lithium)
to check value, or `regex` to match against.

`'mx'` boolean that enable validator to check if MX DNS record exists

*You can achieve lithium's default behavior with __options__ `'mx' => false, 'pattern' => false`.*

By default this filter check against custom regex that doesn't match all
[RFC 5322](http://tools.ietf.org/html/rfc5322) valid  emails, but will match against most correct
emails, and doesn't check domain against MX DNS record.
`'mx' => false, 'pattern' => '/^[a-z0-9][a-z0-9_.-]*@[a-z0-9.-]{3,}\.[a-z]{2,4}$/i'`

___



## Eval comparation builder:

`li3_validators\extensions\util\EvalComparation::build(array $options)`

**Options:**

`conditions` array - This array will be converted to eval string

`values` array - Associative array of values that will be used in generated condition

Best way to understand this utility method is example:

```

	$options = array(
		'conditions' => array(array('name', '===', 'diff_test_name')),
		'values' => array('name' => 'test_name')
	);

	$eval_one = EvalComparation::build($options); // 'return (('test_name' === 'diff_test_name'));'


	$options = array(
		'conditions' => array(
			array('name', '===', 'diff_test_name'), '||', array('name', '===', 'test_name')
		),
		'values' => array('name' => 'test_name')
	);

	$eval_two = EvalComparation::build($options); // 'return (('test_name' === 'diff_test_name') || ('test_name' === 'test_name'));'

```

`eval($eval_one)` will evaluate `false`

`eval($eval_two)` will evaluate `true`

You can build nested conditions as well:

```

	$options = array(
		'conditions' => array(
			array('name', '===', 'diff_test_name'), '&&',
			array(
				array('other_field', '===', null), '||', array('other_field', '===', 'correct')
			)
		),
		'values' => array('name' => 'test_name', 'other_field' => 'other_field_val')
	);

	$eval_tree = EvalComparation::build($options);
	// 'return (('test_name' === 'diff_test_name') && (('other_field_val' === null) || ('other_field_val' === 'correct')));'

```

___



## Project status

[![Build Status](https://travis-ci.org/djordje/li3_validators.png?branch=master)]
(https://travis-ci.org/djordje/li3_validators)
[![project status](http://stillmaintained.com/djordje/li3_validators.png)]
(http://stillmaintained.com/djordje/li3_validators)