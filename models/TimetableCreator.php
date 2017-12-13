<?php

namespace app\models;


class TimetableCreator extends \yii\base\Model
{
    public $datestart;
    public $dateend;

    public function rules()
    {
        return [
            [['datestart', 'dateend'], 'required', 'message' => 'Обов\'язкове поле'],
        ];
    }
}