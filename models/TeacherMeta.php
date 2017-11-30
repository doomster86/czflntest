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
        $rank_values = $this->rank->find()->asArray()->select('rank_name')->orderBy('ID')->all();

        $rank_values = ArrayHelper::getColumn($rank_values, 'rank_name');

        $rank_ids = $this->rank->find()->asArray()->select('ID')->orderBy('ID')->all();
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
        $degree_values = $this->degree->find()->asArray()->select('degree_name')->orderBy('ID')->all();
        $degree_values = ArrayHelper::getColumn($degree_values, 'degree_name');

        $degree_ids = $this->degree->find()->asArray()->select('ID')->orderBy('ID')->all();
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
        $skill_values = $this->skill->find()->asArray()->select('skill_name')->orderBy('ID')->all();
        $skill_values = ArrayHelper::getColumn($skill_values, 'skill_name');

        $skill_ids = $this->skill->find()->asArray()->select('ID')->orderBy('ID')->all();
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
}
