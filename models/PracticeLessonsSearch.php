<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class PracticeLessonsSearch extends PracticeLessons {

    public function rules() {
        return [
            [['ID', 'course_id', 'practice_id', 'quantity'], 'integer'],
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = PracticeLessons::find();

        // add conditions that should always apply here

        $dataProviderPractice = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProviderPractice;
        }

        // grid filtering conditions cюда цифры
        $query->andFilterWhere([
            'ID' => $this->ID,
            'course_id' => $params['course_id'],
            'practice_id' => $this->practice_id,
            'quantity' => $this->quantity
        ]);

        // сюда буквы
        $query->andFilterWhere([]);

        return $dataProviderPractice;
    }

}