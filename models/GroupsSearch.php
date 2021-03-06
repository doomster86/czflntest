<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Groups;

/**
 * GroupsSearch represents the model behind the search form about `app\models\Groups`.
 */
class GroupsSearch extends Groups {

    public $userName;
	public $coursesName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'course'], 'integer'],
            [['name', 'coursesName', 'curator', 'userName'], 'safe'],
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
	            'coursesName' => [
		            'asc' => ['courses.name' => SORT_ASC],
		            'desc' => ['courses.name' => SORT_DESC],
		            'label' => 'Професія'
	            ],
                'curator',
                'userName' => [
                    'asc' => ['user.firstname' => SORT_ASC],
                    'desc' => ['user.firstname' => SORT_DESC],
                    'label' => 'Ім\'я куратора'
                ],
                'date_start' => [
                    'asc' => ['date_start' => SORT_ASC],
                    'desc' => ['date_start' => SORT_DESC],
                    'label' => 'Дата посадки'
                ],
                'date_end' => [
                    'asc' => ['date_end' => SORT_ASC],
                    'desc' => ['date_end' => SORT_DESC],
                    'label' => 'Дата закінчення навчання'
                ],

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

        $query->andFilterWhere(['like', 'groups.name', $this->name]);

	    $query->joinWith(['courses' => function ($q) {
		    $q->where('courses.name LIKE "%' . $this->coursesName . '%"');
	    }]);

        $query->joinWith(['user' => function ($q) {
            $pieces = explode(" ", $this->userName);
            $userFirstName = $pieces[0];
            if (!empty($pieces[1])) {
            $userLastName = $pieces[1];
            }

            if (empty($userLastName)) {
                $q->where('firstname LIKE "%' . $userFirstName . '%" ' . 'OR lastname LIKE "%' . $userFirstName . '%"');
            } else {
                $q->where('firstname LIKE "%' . $userFirstName . '%" ' . 'OR lastname LIKE "%' . $userLastName . '%" OR firstname LIKE "%' . $userLastName . '%" ' . 'OR lastname LIKE "%' . $userFirstName . '%" ');

            }
        }]);


        return $dataProvider;
    }
}
