<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rank".
 *
 * @property integer $ID
 * @property string $rank
 */
class Rank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rank'], 'required'],
            [['rank'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'rank' => 'Rank',
        ];
    }
}
