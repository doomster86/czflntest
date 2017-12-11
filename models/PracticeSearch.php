<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Practice;

/**
 * SubjectsSearch represents the model behind the search form about `app\models\Subjects`.
 */
class PracticeSearch extends Practice {

    public $teacherName;
	public $audienceName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'max_week'], 'integer', 'message' => 'Повинно бути числом'],
            [['name', 'master_id', 'teacherName', 'audienceName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Practice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'name',
                'master_id',
                'teacherName' => [
                    'asc' => ['user.firstname' => SORT_ASC],
                    'desc' => ['user.firstname' => SORT_DESC],
                    'label' => 'Викладач'
                ],
	            'audienceName' => [
		            'asc' => ['audience.name' => SORT_ASC],
		            'desc' => ['audience.name' => SORT_DESC],
		            'label' => 'Аудиторія'
	            ],
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
            'max_week' => $this->max_week

            //'name' => $this->name,
            //'teacher' => $this->teacher,


        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'master_id', $this->master_id]);

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
