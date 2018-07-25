<?php

namespace app\models;


class AddGroupToTable extends \yii\base\Model
{
    public $gid;
    public $id;

    public function rules()
    {
        return [
            [['gid', 'id'], 'required'],
            [['gid','id'], 'integer'],
        ];
    }
}