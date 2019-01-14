<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rnp_subjects".
 *
 * @property int $ID
 * @property int $rnp_id
 * @property int $plan_all
 * @property string $title
 */
class RnpSubjects extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rnp_subjects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rnp_id', 'plan_all', 'title'], 'required'],
            [['rnp_id', 'plan_all'], 'integer'],
            [['title'], 'string', 'max' => 255],
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
            'plan_all' => 'Plan All',
            'title' => 'Title',
        ];
    }

    public function getNakaz() {
        return $this->hasOne(Nakaz::className(), ['subject_id' => 'ID']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'teacher_id'])->viaTable('nakaz', ['subject_id' => 'ID']);
    }

    public function getTeacherName() {
        return $this->user->firstname . ' ' . $this->user->middlename . ' ' . $this->user->lastname;
    }
}
