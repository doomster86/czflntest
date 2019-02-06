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
    public $professionName;
    public $audienceName;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'teacherName', 'audienceName', 'professionName'], 'safe'],
        ];
    }


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
        $query->joinWith(['audience' => function ($q) {
            $q->where('audience.name LIKE "%' . $this->audienceName . '%"');
        }]);

        $query->joinWith(['profession' => function ($q) {
            $q->where('courses.name LIKE "%' . $this->professionName . '%"');
        }]);

        $query->joinWith(['user' => function ($q) {
            $pieces = explode(" ", $this->teacherName);
            $userFirstName = $pieces[0];
            if (!empty($pieces[1])) {
                $userLastName = $pieces[1];
            }

            if (empty($userLastName)) {
                $q->where('firstname LIKE "%' . $userFirstName . '%" ' . 'OR lastname LIKE "%' . $userFirstName . '%"');
            } else {
                $q->where('firstname LIKE "%' . $userFirstName . '%" ' . 'OR lastname LIKE "%' . $userLastName . '%" OR firstname LIKE "%' . $userLastName . '%" ' . 'OR lastname LIKE "%' . $userFirstName . '%" ');

            }
        }]);

        return $dataProvider;
    }
}