<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.01.2019
 * Time: 11:48
 */

namespace app\models;

use yii\data\ActiveDataProvider;

class RnpSubjectsSearch extends RnpSubjects
{
    public $teacherName;

    public function search($params) {
        $query = RnpSubjects::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'name',
                'teacher_id',
                'teacherName' => [
                    'asc' => ['user.firstname' => SORT_ASC],
                    'desc' => ['user.firstname' => SORT_DESC],
                    'label' => 'Ім\'я куратора'
                ],
                'audienceName' => [
                    'asc' => ['audience.name' => SORT_ASC],
                    'desc' => ['audience.name' => SORT_DESC],
                    'label' => 'Аудиторія'
                ],
                'audience_id',
                'required',
                'max_week',
                'practice',
            ]
        ]);

        $this->load($params);

        $query->andFilterWhere(['like', 'title', $this->title]);
        return $dataProvider;
    }
}