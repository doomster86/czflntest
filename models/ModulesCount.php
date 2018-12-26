<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modules_count".
 *
 * @property int $ID
 * @property int $rnp_id
 * @property int $module_id
 * @property int $count
 */
class ModulesCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modules_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rnp_id', 'module_id', 'count'], 'required'],
            [['rnp_id', 'module_id', 'count'], 'integer'],
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
            'module_id' => 'Module ID',
            'count' => 'Count',
        ];
    }
}
