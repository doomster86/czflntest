<?php

namespace app\models;

use yii\base\Model;


class CoursesForm extends Model
{
    public $name; //название курса
    public $teacher; //преподаватель курса
    public $group; //группа
    public $lections; //кол-во лекций
    public $practics; //кол-во практических занятий

    public function rules()
    {
        return [
            [['name', 'teacher', 'group', 'lections', 'practics'], 'required'],
        ];
    }

}