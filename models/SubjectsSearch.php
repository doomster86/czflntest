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

	public $teacher;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ID', 'max_week', 'required'], 'integer', 'message' => 'Повинно бути числом'],
            [['name', 'teacher_id', 'audience_id', 'teacher'], 'safe'],
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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'max_week' => $this->max_week,

            //'name' => $this->name,
	        /*
            'teacher' => [
	            'asc' => ['.corps_name' => SORT_ASC],
	            'desc' => ['corps.corps_name' => SORT_DESC],
	            'label' => 'Корпус'
            ]
*/

        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'teacher_id', $this->teacher_id]);

        return $dataProvider;
    }
}
