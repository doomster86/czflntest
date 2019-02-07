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

        $query->andFilterWhere(['like', 'rnp_subjects.title', $this->title]);
        $query->joinWith(['audience' => function ($q) {
            $q->where('audience.name LIKE "%' . $this->audienceName . '%" OR audience.name IS NULL');
        }]);

        $query->joinWith(['profession' => function ($q) {
            $q->where('courses.name LIKE "%' . $this->professionName . '%"');
        }]);

        $query->joinWith(['user' => function ($q) {
            $q->where('concat_ws(\' \',firstname,middlename,lastname) like "%' . $this->teacherName . '%"');
        }]);

        return $dataProvider;
    }
}