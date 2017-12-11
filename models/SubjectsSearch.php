<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Subjects;
/**
 * SubjectsSearch represents the model behind the search form about `app\models\Subjects`.
 */
class SubjectsSearch extends Subjects {

    public $teacherName;
    public $audienceName;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ID', 'teacher_id', 'audience_id', 'required', 'max_week'], 'integer'],
            [['name', 'teacherName', 'audienceName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Subjects::find();

        // add conditions that should always apply here


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
                'max_week'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'teacher_id' => $this->teacher_id,
            'audience_id' => $this->audience_id,
            'required' => $this->required,
            'max_week' => $this->max_week,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        $query->joinWith(['user' => function ($q) {
            $q->where('firstname LIKE "%' . $this->teacherName . '%" ' . 'OR middlename LIKE "%' . $this->teacherName . '%" ' . 'OR lastname LIKE "%' . $this->teacherName . '%"');
        }]);

        $query->joinWith(['audience' => function ($q) {

            $pieces = explode(" ", $this->audienceName);
            $firstWord = $pieces[0];
            if (!empty($pieces[1])) {
                $secondWord = $pieces[1];
            }

            if (empty($secondWord)) {
                $q->where('audience.name LIKE "%' . $firstWord . '%" ');
            } else {
                $q->where('audience.name LIKE "%' . $firstWord . '%" ' . 'OR audience.name LIKE "%' . $secondWord . '%"');
            }
        }]);

        return $dataProvider;
    }
}
