<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21.09.2017
 * Time: 17:36
 */

namespace app\models;

use yii\base\Model;

class MyEntryForm extends Model {

    public $name;
    public $email;

    public function rules(){
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
        ];
    }



}