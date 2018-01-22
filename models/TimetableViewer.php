<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.01.2018
 * Time: 17:12
 */

namespace app\models;


class TimetableViewer extends \yii\base\Model
{
	public $teacher_id;
	public $group_id;

	public function rules()
	{
		return [
			[['teacher_id', 'group_id'], 'integer'],
		];
	}
}