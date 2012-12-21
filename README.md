# Collection of reusable validators for [Lithium PHP Framework](https://github.com/UnionOfRAD/lithium)

## Table of content:

* **[Custom validators:](#custom-validators)**
  * Unique
  * Confirm
  * Dependencies
  * Compare with old db value
  * Conditional in range
* **[Overridden validators:](#overridden-validators)**
  * [Email](#email-validator)
* **[Eval comparation builder](#eval-comparation-builder)**
* **[Project status](#project-status)**

## Custom validators:

## Overridden validators:

###### Email validator

**Options:**

`'pattern'` mixed (false|regex) - If `false` use php `filter_var()` function (default in Lithium)
to check value, or `regex` to match against.

`'mx'` boolean that enable validator to check if MX DNS record exists

*You can achieve lithium's default behavior with __options__ `'mx' => false, 'pattern' => false`.*

By default this filter check against custom regex that doesn't match all
[RFC 5322](http://tools.ietf.org/html/rfc5322) valid  emails, but will match against most correct
emails, and doesn't check domain against MX DNS record.
`'mx' => false, 'pattern' => '/^[a-z0-9][a-z0-9_.-]*@[a-z0-9.-]{3,}\.[a-z]{2,4}$/i'`

## Eval comparation builder:

## Project status

[![Build Status](https://travis-ci.org/djordje/li3_validators.png?branch=master)]
(https://travis-ci.org/djordje/li3_validators)
[![project status](http://stillmaintained.com/djordje/li3_validators.png)]
(http://stillmaintained.com/djordje/li3_validators)