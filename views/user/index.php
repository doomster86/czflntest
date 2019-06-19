<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use app\assets\Relevator;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* https://github.com/QODIO/revealator */

$this->title = 'Користувачі';
$this->params['breadcrumbs'][] = $this->title;

Relevator::register($this);
?>
<div class="user-index">
<style>
    .row-flex {
        display: flex;
        flex-flow: row wrap;
    }
</style>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // echo Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php /**
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'summary' => "<div class='summary'>Показано {begin} - {end} з {totalCount} професій</div>",
        'tableOptions' => [
            'class' => 'table table-striped table-bordered col-xs-12 user-table'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'id',
                'label'=>'ID',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }
            ],

            [
                'attribute'=>'username',
                'label'=>'Логін',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-2'];
                }
            ],

            [
                'attribute'=>'email',
                'label'=>'Email',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-2'];
                }
            ],

            [
                'attribute'=>'status',
                'label'=>'Статус',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-2'];
                },
                'content'=>function($data){
                    return $data->getStatusType();
                }
            ],

            [
                'attribute'=>'created_at',
                'label'=>'Дата реєстрації',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-2'];
                },
                'content'=>function($data){
                    return $data->getRegisterDate();
                }
            ],

            [
                'attribute'=>'role',
                'label'=>'Роль',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                },
                'content'=>function($data){
                    return $data->getRoleType();
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [

                    'update' => function ($url,$model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil left"></span>',
                            $url,
                            [
                                'title' => 'Редагувати',
                                'data-pjax' => '0',
                            ]
                        );
                    },

                    'delete' => function ($url,$model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash right"></span>',
                            $url,
                            [
                                'title' => 'Видалити',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ],
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }
            ],
        ],
    ]);
?>
<?php Pjax::end(); ?>
 */ ?>

    <h2 class="scroller">Гортайте вниз для перегляду ↓↓↓</h2>
    <h4 class='clicktxt'>Натисніть на зображення для редагування</h4>
    <div class="row">
        <div class="form-group">
            <div class="col-xs-5 col-sm-4 col-md-4 col-lg-3">
                <?php
                $search = '';
                if (!empty($_POST['UserSearch']['search'])) {
                    $search = $_POST['UserSearch']['search'];
                } else if (!empty($_GET['search'])) {
                    $search = $_GET['search'];
                }
                $form = ActiveForm::begin();
                echo $form->field($model, 'role')->dropDownList(ArrayHelper::map(User::ROLES, 'roles', 'name'),
                    [
                        'prompt' => 'Всі користувачі',
                        'onchange' => '$.pjax.reload({container: "#w0", url: "' . Url::to(['user/index']) . '?search='.$search.'", data: {role: $(this).val()}});',
                        'options' => isset($_GET['role']) ? [$_GET['role'] => ["selected" => true]] : '',
                    ]
                )->label('Роль');;
                ActiveForm::end();
                ?>
            </div>
            <div class="col-xs-5 col-sm-4 col-md-4 col-lg-3">
                <?php
                if (!empty(Yii::$app->request->post()['UserSearch']['search'])) {
                    $searchResult = Yii::$app->request->post()['UserSearch']['search'];
                }else if (!empty($_GET['search'])) {
                    $searchResult = $_GET['search'];
                } else {
                    $searchResult = '';
                }
                $form = ActiveForm::begin(); ?>

                <?= $form->field($searchModel, 'search')->textInput(['value' => $searchResult])->label('Пошук'); ?>
            </div>
            <div class="col-xs-5 col-sm-4 col-md-4 col-lg-3">
                <div class="form-group" style="margin-top: 25px;">
                    <?= Html::submitButton('Пошук', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            </div>
        </div>
    </div>
        <?php
    Pjax::begin();
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_list',
        'layout' => "{items}\n{summary}\n{pager}",
        'summary' => "<div class='summary'>Показано {begin} - {end} з {totalCount} користувачів</div>",
        'options' => ['id'    => 'userlist-wrapper', 'class' => 'row-flex'],
        'itemOptions' =>function ($model, $key, $index, $widget){
            return [
                'tag' => 'div',
                'class' => 'col-xs-12 col-sm-6 col-md-4 left revealator-once revealator-slideleft revealator-delay'.$index,
            ];
        }
    ]);
    ?>
    <?php Pjax::end(); ?>
    <p>
        <?php
        $request = Yii::$app->request;
        $status = $request->get('status');
        if ($status == 'all') {
            echo Html::a('Не показувати заблокованих', ['index'], ['class' => 'btn btn-primary']);
        } else {
            echo Html::a('Показати заблокованих', ['index', 'status' => 'all'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

</div>
