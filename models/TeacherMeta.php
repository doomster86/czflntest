<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class TeacherMeta extends \yii\db\ActiveRecord
{
    const TEACHER_TYPE = [
        '0' => 'Майстер',
        '1' => 'Штатний викладач',
        '2' => 'Позаштатний викладач',
    ];

    /* Связь с моделью Rank*/
    public function getRank()
    {
        return $this->hasOne(Rank::className(), ['ID' => 'rank_id']);
    }

    /* Геттер для названия Пед.звання */
    public function getRankName() {
        return $this->rank->rank_name;
    }

    /* Геттер селекта Пед.звання */
    public function  getAllRanks() {
        $rank_values = Rank::find()->asArray()->select('rank_name')->orderBy('ID')->all();

        $rank_values = ArrayHelper::getColumn($rank_values, 'rank_name');

        $rank_ids = Rank::find()->asArray()->select('ID')->orderBy('ID')->all();
        $rank_ids = ArrayHelper::getColumn($rank_ids, 'ID');

        $ranks = array_combine($rank_ids,$rank_values);

        return $ranks;
    }

    /* Связь с моделью Degree*/
    public function getDegree()
    {
        return $this->hasOne(Degree::className(), ['ID' => 'degree_id']);
    }

    /* Геттер для названия Вч.ступінь */
    public function getDegreeName() {
        return $this->degree->degree_name;
    }

    /* Геттер селекта Вч.ступінь */
    public function  getAllDegrees() {
        $degree_values = Degree::find()->asArray()->select('degree_name')->orderBy('ID')->all();
        $degree_values = ArrayHelper::getColumn($degree_values, 'degree_name');

        $degree_ids = Degree::find()->asArray()->select('ID')->orderBy('ID')->all();
        $degree_ids = ArrayHelper::getColumn($degree_ids, 'ID');

        $degrees = array_combine($degree_ids,$degree_values);

        return $degrees;
    }

    /* Связь с моделью Skill*/
    public function getSkill()
    {
        return $this->hasOne(Skill::className(), ['ID' => 'skill_id']);
    }

    /* Геттер для названия Кваліфікація */
    public function getSkillName() {
        return $this->skill->skill_name;
    }

    /* Геттер селекта Кваліфікація */
    public function  getAllSkills() {
        $skill_values = Skill::find()->asArray()->select('skill_name')->orderBy('ID')->all();
        $skill_values = ArrayHelper::getColumn($skill_values, 'skill_name');

        $skill_ids = Skill::find()->asArray()->select('ID')->orderBy('ID')->all();
        $skill_ids = ArrayHelper::getColumn($skill_ids, 'ID');

        $skills = array_combine($skill_ids,$skill_values);

        return $skills;
    }

    public static function tableName()
    {
        return 'teacher_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_type', 'rank_id', 'degree_id', 'skill_id'], 'required'],
            [['user_id', 'teacher_type', 'rank_id', 'degree_id', 'skill_id', 'hours', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], 'integer'],
        ];
    }

    public function getTeacherType($user_id) {
        $type_id = TeacherMeta::find()->asArray()->select('teacher_type')->where(['user_id' => $user_id])->one();
        if ($type_id) {
            $type = self::TEACHER_TYPE[$type_id['teacher_type']];
            return $type;
        } else {
            return 'Тип викладача не обраний';
        }
    }

    public function getTeacherWorkDays($user_id)
    {
        $days_array = TeacherMeta::find()->asArray()->select(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->where(['user_id' => $user_id])->all();
        $days_array = $days_array[0];
        $days_array = array_values($days_array);
        $arr_length = count($days_array);

        $day_names = array(0 => 'Пн', 1 => 'Вт', 2 => 'Ср', 3 => 'Чт', 4 => 'Пт', 5 => 'Сб', 6 => 'Нд');
        $result = array();
        for ($i = 0; $i < $arr_length; $i++) {
            if ($days_array[$i]) {
                $result[] = $day_names[$i];
            }
        }
        return implode('. ', $result);
    }

}
