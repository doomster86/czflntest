<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Audience;

/**
 * AudienceSearch represents the model behind the search form about `app\models\Audience`.
 */
class AudienceSearch extends Audience {

    public $corpsName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'corps_id'], 'integer'],
            [['name', 'num', 'corpsName'], 'safe'],
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
        $query = Audience::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'name',
                'num',
                'corps_id',

                'corpsName' => [
                    'asc' => ['corps.name' => SORT_ASC],
                    'desc' => ['corps.name' => SORT_DESC],
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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'num', $this->num]);

        $query->joinWith(['corps' => function ($q) {
            $q->where('corps.name LIKE "%' . $this->corpsName . '%"');
        }]);

        return $dataProvider;
    }
}
