<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Corps;
/**
 * This is the model class for table "audience".
 *
 * @property integer $ID
 * @property string $name
 * @property string $num
 * @property integer $corps_id
 */
class Audience extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'audience';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'num', 'corps_id'], 'required'],
            [['corps_id'], 'integer'],
            [['name', 'num'], 'string', 'max' => 255],
            [['corps_id'], 'exist', 'skipOnError' => true, 'targetClass' => Corps::className(), 'targetAttribute' => ['corps_id' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'id',
            'name' => 'Назва',
            'num' => 'Номер',
            'corps_id' => 'Корпус',
            'corpsName' => 'Корпус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCorps() {
        return $this->hasOne(Corps::className(), ['ID' => 'corps_id']);
    }

    public function getCorpsName() {
        return $this->corps->name;
    }

    public function getCorpsNames() {

        $corps_values = Corps::find()->asArray()->select('name')->orderBy('ID')->all();
        $corps_values = ArrayHelper::getColumn($corps_values, 'name');

        $corps_ids = Corps::find()->asArray()->select('ID')->orderBy('ID')->all();
        $corps_ids = ArrayHelper::getColumn($corps_ids, 'ID');

        $corps = array_combine($corps_ids,$corps_values);

        $corps_add = array( 0 => 'Оберіть корпус');
        $corps = ArrayHelper::merge($corps_add, $corps);

        return $corps;
    }

}
