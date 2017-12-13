<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TimetableParts;

/**
 * TimetablePartsSearch represents the model behind the search form about `app\models\TimetableParts`.
 */
class TimetablePartsSearch extends TimetableParts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'datestart', 'dateend', 'cols', 'rows'], 'integer'],
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
        $query = TimetableParts::find();

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
            'id' => $this->id,
            'datestart' => $this->datestart,
            'dateend' => $this->dateend,
            'cols' => $this->cols,
            'rows' => $this->rows,
        ]);

        return $dataProvider;
    }
}
