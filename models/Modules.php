<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modules".
 *
 * @property int $ID
 * @property int $rnp_id
 * @property int $module
 * @property int $hours
 * @property int $count
 * @property int $subject_id
 */
class Modules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rnp_id', 'module', 'hours', 'count', 'subject_id'], 'required'],
            [['rnp_id', 'module', 'hours', 'count', 'subject_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'rnp_id' => 'Rnp ID',
            'module' => 'Module',
            'hours' => 'Hours',
            'count' => 'Count',
            'subject_id' => 'Subject ID',
        ];
    }
}
