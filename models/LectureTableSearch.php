<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use app\models\LectureTable;

/**
 * LectureTableSearch represents the model behind the search form about `app\models\LectureTable`.
 */
class LectureTableSearch extends LectureTable {

	public $corpsName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'corps_id'], 'integer'],
            [['time_start', 'time_stop', 'corpsName'], 'safe'],
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
    public function search($params) {
        $query = LectureTable::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

	    $dataProvider->setSort([
		    'attributes' => [
		        'ID',
		        'time_start',
		        'time_stop',
		        'corps_id',

			    'corpsName' => [
				    'asc' => ['corps.corps_name' => SORT_ASC],
				    'desc' => ['corps.corps_name' => SORT_DESC],
				    'label' => 'Корпус'
			    ]
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
            'corps_id' => $this->corps_id,
        ]);

        $query->andFilterWhere(['like', 'time_start', $this->time_start])
            ->andFilterWhere(['like', 'time_stop', $this->time_stop]);

	    $query->joinWith(['corps' => function ($q) {
		    $q->where('corps.corps_name LIKE "%' . $this->corpsName . '%"');
	    }]);

        return $dataProvider;
    }
}
