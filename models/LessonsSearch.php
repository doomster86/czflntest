<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class LessonsSearch extends Lessons {

    public function rules() {
        return [
            [['ID', 'course_id', 'subject_id', 'quantity'], 'integer'],
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = Lessons::find();

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

        // grid filtering conditions cюда цифры
        $query->andFilterWhere([
            'ID' => $this->ID,
            'course_id' => $this->course_id,
            'subject_id' => $this->subject_id,
            'quantity' => $this->quantity
        ]);

        // сюда буквы
        $query->andFilterWhere([]);

        return $dataProvider;
    }

}