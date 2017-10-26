<?php

namespace app\models;

use yii\base\Model;


class CoursesForm extends Model
{
    public $name; //назва курсу
    public $pract; //кількість занять виробничої практики
    public $worklect; //кількість занять виробничого навчання
    public $teorlect; //кількість занять теоритичного навчання
    public $subject; //предмети

    public function rules()
    {
        return [
            [['name', 'subject','pract', 'worklect', 'teorlect'], 'required', 'message'=>'Обовязкове поле'],
        ];
    }

}