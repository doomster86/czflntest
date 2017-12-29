<?php

namespace app\controllers;

use Yii;
use app\models\TimetableParts;
use app\models\TimetablePartsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Timetable;

/**
 * TimetablePartsController implements the CRUD actions for TimetableParts model.
 */
class TimetablePartsController extends Controller
{
    /**
     * Lists all TimetableParts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TimetablePartsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TimetableParts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TimetableParts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TimetableParts();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /*
            * получаем дату начала и конца генерации расписания
            * формируем сетку
            * - формируем кол-во столбцов по количеству дней от начала до конца дат генерации
            * - формируем кол-во строк по максимальному количеству пар среди корпусов
            * записываем в тиблицу timetable_parts даты начала и конца генерации расписания, количество строки и столбцов
            */

            /*
            * внутри метода вызываем другой, который по заданным правилам наполняет сетку занятиями
            * указывает координаты ячейки расписания в сетке с данным id
            * Правила формирования:
            *  - первой парой в курсе у группы всегда ставить вводную лекцию
            *  - у группы и у преподавателя не могут быть пары в один день в разных корпусах
            *  - если есть производственная практика, то в этот день других занятий не ставить
            *  - учитывать максимальную нагрузку преподавателей в неделю и не превышать её
            *  - учитывать рабочие дни преподавателей и не ставить им пары в другие дни недели
            *  - не превышать заданное кол-во занятий по указанному предмету
            *  - не ставить один и тот же предмет в один день несколько раз
            *  - по возможности, ставить одинаковые предметы через день
            */
            $model->seveTimetableParts();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TimetableParts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TimetableParts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Timetable::deleteAll(['=', 'part_id', $id]);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TimetableParts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TimetableParts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TimetableParts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
