<?php

namespace laxity7\yii2\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;

/**
 * TrimBehavior automatically truncates all spaces and other characters in all attributes in a model before validate.
 *
 * Behavior skips any non-string values.
 *
 * To use this behavior, insert the following code to your Model/ActiveRecord class:
 *
 * ```php
 * public function behaviors()
 * {
 *     $behaviors = parent::behaviors();
 *
 *     // simple trim all attributes
 *     $behaviors['trim'] = TrimBehavior::class,
 *     // or
 *     // extended use
 *     $behaviors['trim'] = [
 *         'class'  => TrimBehavior::class,
 *         'fields' => ['name'], // if it's empty, then all attributes will be processed
 *         'mask'   => '_' . TrimBehavior::DEFAULT_MASK,
 *         'once'   => false,
 *     ];
 *
 *     return $behaviors;
 * }
 * ```
 *
 * @property Model $owner
 *
 * @author Vlad Varlamov <work@laxity.ru>
 */
class TrimBehavior extends Behavior
{
	const DEFAULT_MASK = " \t\n\r\0\x0B";

	/** @var array List of fields to process. By default all fields */
	protected $fields = [];
	/** @var string Simply list all characters that you want to be stripped */
	protected $mask = self::DEFAULT_MASK;
	/** @var bool Run the behavior once and detach after */
	protected $once = true;

	/** @inheritdoc */
	public function events()
	{
		return [
			Model::EVENT_BEFORE_VALIDATE => 'trimAttributes',
		];
	}

	/**
	 * Trim attributes
	 *
	 * @param Event $event
	 *
	 * @internal
	 */
	public function trimAttributes(Event $event)
	{
		$attributes = $this->owner->getAttributes($this->fields ?: null);
		foreach ($attributes as $key => $value) {
			if (is_string($value) && !empty($value)) {
				$this->owner->$key = trim($value, $this->mask);
			}
		}

		if ($this->once) {
			$this->detach();
		}
	}

	/**
	 * @param array $fields
	 */
	public function setFields(array $fields)
	{
		$this->fields = $fields;
	}

	/**
	 * @param $mask
	 */
	public function setMask($mask)
	{
		$this->mask = $mask;
	}

	/**
	 * @param bool $once
	 */
	public function setOnce($once)
	{
		$this->once = $once;
	}
}