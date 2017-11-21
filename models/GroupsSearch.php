<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Groups;

/**
 * GroupsSearch represents the model behind the search form about `app\models\Groups`.
 */
class GroupsSearch extends Groups
{

    public $userFirstName;
    public $userLastName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'course'], 'integer'],
            [['name', 'curator', 'userFirstName', 'userLastName'], 'safe'],
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
        $query = Groups::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'ID',
                'name',
                'course',
                'curator',
                'userFirstName' => [
                    'asc' => ['user.firstname' => SORT_ASC],
                    'desc' => ['user.firstname' => SORT_DESC],
                    'label' => 'Ім\'я куратора'
                ],
                'userLastName' => [
                    'asc' => ['user.lastname' => SORT_ASC],
                    'desc' => ['user.lastname' => SORT_DESC],
                    'label' => 'Прізвище куратора'
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
            'course' => $this->course,
            'curator' => $this->curator,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        $query->joinWith(['user' => function ($q) {
            $q->where('user.firstname LIKE "%' . $this->userName . '%"');
        }]);


        return $dataProvider;
    }
}
