<?php

namespace app\models;

use yii\base\Model;


class CoursesForm extends Model
{
    public $name; //назва курсу
    public $teacher; //викладач курсу
    public $group; //група
    public $pract; //кількість занять виробничої практики
    public $worklect; //кількість занять виробничого навчання
    public $teorlect; //кількість занять теоритичного навчання

    public function rules()
    {
        return [
            [['name', 'teacher', 'group', 'pract', 'worklect', 'teorlect'], 'required', 'message'=>'Обовязкове поле'],
        ];
    }

}