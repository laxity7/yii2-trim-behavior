# Yii2 TrimBehavior

[![License](https://img.shields.io/github/license/laxity7/yii2-trim-behavior.svg)](https://github.com/laxity7/yii2-trim-behavior/blob/master/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/laxity7/yii2-trim-behavior.svg)](https://packagist.org/packages/laxity7/yii2-trim-behavior)
[![Total Downloads](https://img.shields.io/packagist/dt/laxity7/yii2-trim-behavior.svg)](https://packagist.org/packages/laxity7/yii2-trim-behavior)

This behavior automatically truncates all spaces and other characters in all attributes in a model before validate.
The easiest way is to add this behavior to the parent class so that all attributes are always trimmed.

> Note: Behavior automatically skips any non-string and empty values.

## Install

Install via composer 

```shell
composer require laxity7/yii2-trim-behavior
```

Or you may add dependency manually in composer.json:

```
 "laxity7/yii2-trim-behavior": "*"
```

## How to use

To use TrimBehavior, insert the following code to your Model/ActiveRecord class:

```php
/** @inheritdoc */
public function behaviors(): array
{
   return [
       'trimAttributes' => \laxity7\yii2\behaviors\TrimBehavior::class, // trim all attributes
       // ... other behaviors
   ];
}
```
	
You can also pass the following parameters:
- **fields** `string[]` (by default `[]`) List of fields to process. By default all fields.
- **mask** `string` (by default `' \t\n\r '`) Simply list all characters that you want to be stripped.
You can use TrimBehavior::DEFAULT_MASK to combine your characters and defaults
- **once** `string[]` (by default `true`) Run the behavior once and detach after.

So, the complete list of settings will look like this:

```php
/** @inheritdoc */
public function behaviors(): array
{
    return [
        [
            'class'  => \laxity7\yii2\behaviors\TrimBehavior::class,
            'fields' => ['foo', 'bar'],
            'mask'   => '_' . TrimBehavior::DEFAULT_MASK,
            'once'   => false,
        ],
    ];
}
```

If you want to trim only certain fields in a certain class, then better to use the filter in rules of validation

```php
/** @inheritdoc */
public function rules()
{
    return [
        [['filedName1', 'filedName1'], 'filter', 'filter' => 'trim'],
        // ... other rules
    ];
}
```

