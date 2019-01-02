<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modules".
 *
 * @property int $ID
 * @property int $rnp_id
 * @property int $subject_id
 * @property int $column_num
 * @property int $column_plan
 * @property int $column_rep
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
            [['rnp_id', 'subject_id', 'column_num', 'column_plan', 'column_rep'], 'required'],
            [['rnp_id', 'subject_id', 'column_num', 'column_plan', 'column_rep'], 'integer'],
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
            'subject_id' => 'Subject ID',
            'column_num' => 'Column Num',
            'column_plan' => 'Column Plan',
            'column_rep' => 'Column Rep',
        ];
    }
}
