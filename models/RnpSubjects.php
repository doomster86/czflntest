<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rnp_subjects".
 *
 * @property int $ID
 * @property int $rnp_id
 * @property int $plan_all
 * @property string $title
 * @property int $audience_id
 * @property int $required
 * @property int $practice
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
            [['rnp_id', 'plan_all', 'audience_id', 'required', 'practice'], 'integer'],
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
            'audience_id' => 'Audience ID',
        ];
    }

    public function getNakaz() {
        return $this->hasOne(Nakaz::class, ['subject_id' => 'ID']);
    }

    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'teacher_id'])->viaTable('nakaz', ['subject_id' => 'ID'], function ($query) {
            /* @var $query \yii\db\ActiveQuery */
            $query->orderBy(['column_num' => SORT_DESC])->limit(1);
        });
    }

    public function getTeacherName() {
        return $this->user->firstname . ' ' . $this->user->middlename . ' ' . $this->user->lastname;
    }

    public function getProfession() {
        return $this->hasOne(Courses::class, ['ID' => 'prof_id'])->viaTable('rnps', ['ID' => 'rnp_id']);
    }

    public function getProfessionName() {
        return $this->profession->name;
    }

    public function getAudience() {
        return $this->hasOne(Audience::class, ['ID' => 'audience_id']);
    }
    public function getAudienceName() {
        return $this->audience ? $this->audience->name:null;
    }
    public function getAudienceNames() {

        $audience_values = Audience::find()->asArray()->select(["ID", "corps_id", "CONCAT('№ ', num, ' - ', name) AS full_name"])
            //->where(['role' => 2, 'status' => 1])
            ->orderBy('ID')
            ->all();
        $audience_names = ArrayHelper::getColumn($audience_values, 'full_name');
        $audience_ids = ArrayHelper::getColumn($audience_values, 'ID');
        $corps_ids = ArrayHelper::getColumn($audience_values, 'corps_id');

        foreach ($corps_ids as $id) {
            $corps_names[] = Corps::find()->asArray()->select(["corps_name"])
                ->where(['ID' => $id])
                ->orderBy('ID')
                ->one();
        }

        $corps_names = ArrayHelper::getColumn($corps_names, 'corps_name');

        for($i = 0; $i < count($audience_names); $i++ ) {
            $audience_names[$i] = "Корпус: ".$corps_names[$i]." || Аудиторія: ".$audience_names[$i];
        }

        $audience = array_combine($audience_ids, $audience_names);
        //$corps_add = array( 0 => 'Оберіть викладача');
        //$corps = ArrayHelper::merge($corps_add, $corps);
        return $audience;
    }
    public function getTeachersNames() {

        $teacher_values = User::find()->asArray()->select(['id', "CONCAT(firstname, ' ', middlename, ' ',lastname) AS full_name"])
            ->where(['role' => 2, 'status' => 1])
            ->orderBy('id')
            ->all();
        $teacher_names = ArrayHelper::getColumn($teacher_values, 'full_name');
        $teacher_ids = ArrayHelper::getColumn($teacher_values, 'id');

        $teachers = array_combine($teacher_ids, $teacher_names);

        //$corps_add = array( 0 => 'Оберіть викладача');
        //$corps = ArrayHelper::merge($corps_add, $corps);

        return $teachers;
    }

    public function getProfessionNames()
    {

        $prof_values = Rnps::find()->asArray()->select(['prof_id'])->all();
        $prof_ids = ArrayHelper::getColumn($prof_values, 'prof_id');

        foreach ($prof_ids as $prof_id) {
            $prof_names[] = Courses::find()->asArray()->select(["name"])
                ->where(['ID' => $prof_id])
                ->orderBy('ID')
                ->one();
        }
        $profs = array_combine($prof_ids, $prof_names);

        //$corps_add = array( 0 => 'Оберіть викладача');
        //$corps = ArrayHelper::merge($corps_add, $corps);

        return $profs;
    }
    public function getPracticeNames() {
        $practice = array();
        $practice[] = 'Теоретичне навчання';
        $practice[] = 'Практичне навчання';
        $practice[] = 'Виробнича практика';
        return $practice;
    }
}

