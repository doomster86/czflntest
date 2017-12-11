<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "subjects".
 *
 * @property integer $ID
 * @property string $name
 */
class Practice extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'practice';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'master_id', 'max_week', 'audience_id'], 'required', 'message'=>'Обов\'язкове поле'],
            ['name', 'string', 'min' => 3, 'max' => 255, 'message'=>'Мін 3 літери'],
            [['max_week'], 'integer', 'min' => 0, 'message' => 'Тільки цифри'],
	        [['master_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['master_id' => 'id']], //добавил вручную
	        [['audience_id'], 'exist', 'skipOnError' => true, 'targetClass' => Audience::className(), 'targetAttribute' => ['audience_id' => 'ID']], //добавил вручную
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'name' => 'Назва',
            'teacherName' => 'Викладач',
	        'audience_id' => 'Аудиторія',
        ];
    }

	public function getAudience() {
		return $this->hasOne(Audience::className(), ['ID' => 'audience_id']);
	}

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'master_id']);
    }

    public function getTeacherName() {
        $firstname = $this->user->firstname;
        $middlenamde = $this->user->middlename;
        $lastname = $this->user->lastname;
        return $firstname." ".$middlenamde." ".$lastname;
    }

    public function getTeachersNames() {

        $teacher_values = User::find()->asArray()->select(['id', "CONCAT(firstname, ' ', middlename, ' ',lastname) AS full_name"])
            ->where(['role' => 2, 'status' => 1])
            ->orderBy('id')
            ->all();
        $teacher_names = ArrayHelper::getColumn($teacher_values, 'full_name');
        $teacher_ids = ArrayHelper::getColumn($teacher_values, 'id');

        $teachers = array_combine($teacher_ids, $teacher_names);

        //$corps_add = array( 0 => 'Оберіть викладача');
        //$corps = ArrayHelper::merge($corps_add, $corps);

        return $teachers;
    }

	public function getAudienceNames() {

		$audience_values = Audience::find()->asArray()->select(["ID", "corps_id", "CONCAT('№ ', num, ' - ', name) AS full_name"])
			//->where(['role' => 2, 'status' => 1])
			->orderBy('ID')
			->all();
		$audience_names = ArrayHelper::getColumn($audience_values, 'full_name');
		$audience_ids = ArrayHelper::getColumn($audience_values, 'ID');
		$corps_ids = ArrayHelper::getColumn($audience_values, 'corps_id');

		foreach ($corps_ids as $id) {
			$corps_names[] = Corps::find()->asArray()->select(["corps_name"])
				->where(['ID' => $id])
				->orderBy('ID')
				->one();
		}

		$corps_names = ArrayHelper::getColumn($corps_names, 'corps_name');

		for($i = 0; $i < count($audience_names); $i++ ) {
			$audience_names[$i] = "Корпус: ".$corps_names[$i]." || Аудиторія: ".$audience_names[$i];
		}

		$audience = array_combine($audience_ids, $audience_names);
		//$corps_add = array( 0 => 'Оберіть викладача');
		//$corps = ArrayHelper::merge($corps_add, $corps);
		return $audience;
	}

    public function getAudienceName() {
        return Audience::getCorpsNameByAudienceID($this->audience->ID). ' ' . $this->audience->num. ' ' . $this->audience->name;
        //$audience_id = $this->audience->ID;
        //return Audience::getCorpsNameByID(3);
        //return  $this->audience->num. ' ' . $this->audience->name;
    }

}
