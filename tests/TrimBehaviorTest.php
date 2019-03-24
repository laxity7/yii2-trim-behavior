<?php

namespace laxity7\tests\unit\behaviors;

use laxity7\yii2\behaviors\TrimBehavior;
use yii\base\DynamicModel;

class TrimBehaviorTest extends \PHPUnit\Framework\TestCase
{

	private function getAttributes()
	{
		return [
			'fieldNormal'       => 'Value',
			'fieldInt'          => 200,
			'fieldIntStr'       => ' 200 ',
			'fieldStrMultiline' => "\n\n line 1\nLine2\n",
			'fieldArr'          => ['foo' => 'bar'],
			'field1'            => ' Value1 ',
			'fieldUnderscore'   => '_Value2 _',
		];
	}

	private function getTrimmedAttributes()
	{
		return [
			'fieldNormal'       => 'Value',
			'fieldInt'          => 200,
			'fieldIntStr'       => '200',
			'fieldStrMultiline' => "line 1\nLine2",
			'fieldArr'          => ['foo' => 'bar'],
			'field1'            => 'Value1',
			'fieldUnderscore'   => '_Value2 _',
		];
	}

	public function testTrimBehavior()
	{
		// simple trim all attributes
		$model = new DynamicModel($this->getAttributes());
		$model->attachBehavior('trim', TrimBehavior::class);
		$model->validate();

		self::assertEquals($this->getTrimmedAttributes(), $model->getAttributes(), 'Error in "simple trim all attributes"');

		// trim one attribute containing a specific characters
		$model = new DynamicModel($this->getAttributes());
		$model->attachBehavior('trim', [
			'class'  => TrimBehavior::class,
			'fields' => ['fieldUnderscore'],
			'mask'   => '_ ',
		]);
		$model->validate();

		$trimmedValues = $this->getAttributes();
		$trimmedValues['fieldUnderscore'] = 'Value2';
		self::assertEquals($trimmedValues, $model->getAttributes(), 'Error in "trim one attribute containing a specific characters"');

		// check detach
		$model = new DynamicModel($this->getAttributes());
		$model->attachBehavior('trim', TrimBehavior::class);
		$model->validate();
		$model->setAttributes($this->getAttributes(), false);
		$model->validate();

		self::assertEquals($this->getAttributes(), $model->getAttributes(), 'Error in "check detach"');

		// check that it is not detachable
		$model = new DynamicModel($this->getAttributes());
		$model->attachBehavior('trim', [
			'class' => TrimBehavior::class,
			'once'  => false,
		]);
		$model->validate();
		$model->setAttributes($this->getAttributes(), false);
		$model->validate();

		self::assertEquals($this->getTrimmedAttributes(), $model->getAttributes(), 'Error in "check that it is not detachable"');
	}

}