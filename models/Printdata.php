<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "printdata".
 *
 * @property int $ID
 * @property string $dolzh
 * @property string $initial
 * @property string $blockleft
 * @property string $blockright
 */
class Printdata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'printdata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dolzh', 'initial', 'blockleft', 'blockright'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'dolzh' => 'Dolzh',
            'initial' => 'Initial',
            'blockleft' => 'Blockleft',
            'blockright' => 'Blockright',
        ];
    }
}
