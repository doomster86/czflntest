<?php

use yii\helpers\Html;
use app\models\User;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'Фактичне педнавантаження';
$this->params['breadcrumbs'][] = ['label' => 'Користувачі', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$roles = User::ROLES;
$formatter = new \yii\i18n\Formatter;
?>
<div class="form-group row">
    <div class="col-xs-4 col-sm-3 col-md-3 col-lg-2">
        <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
        <?= DatePicker::widget(['name' => 'date',
            'pluginOptions' => [
                'autoclose' => true,
                'startView' => 'year',
                'minViewMode' => 'months',
                'format' => 'mm-yyyy'
            ]]) ?>
    </div>
    <?= Html::submitButton('Переглянути', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>
    <?= Html::button('Роздрукувати', [ 'class' => 'btn btn-primary', 'onclick' => 'javascript:xport.toXLS(\'tableGodyny\', \'outputdata\');' ]); ?>
</div>
<div class="table-responsive">
    <table class="table table-bordered" id="tableGodyny">
        <thead>
        <tr>
            <th>№</th>
            <th>ПІБ викладача</th>
            <th>Професія</th>
            <th>Група</th>
            <th>Термін навчання</th>
            <th>Навчальні предмети</th>
            <th>Вид занят. (теор. нав., вир. нав.)</th>
            <?php
            if (!empty($table[0]['timetable'])) {
                foreach ($table[0]['timetable'] as $col) {
                    ?>
                    <th>
                        <?php
                        echo $formatter->asDate($col['date'], 'dd.MM.yyyy');
                        ?>
                    </th>
                    <?php
                }
            }
            ?>
            <th>Факт.вич. за поточ. міс., год.</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($table)) {
            $num = 1;
            foreach ($table as $row) {
                ?>
                <tr>
                    <th><?= $num; ?></th>
                    <th><?= $user->lastname . ' ' . mb_substr($user->firstname, 0, 1) . '.' . mb_substr($user->middlename, 0, 1) . '., ' . mb_strtolower($roles[$user->role]['name']); ?></th>
                    <th><?= $row['course']['name']; ?></th>
                    <th><?= $row['group']['name']; ?></th>
                    <th><?= ($row['group']['date_start'] && $row['group']['date_end'] ? $formatter->asDate($row['group']['date_start'], 'dd.MM.yyyy') . " - " . $formatter->asDate($row['group']['date_end'], 'dd.MM.yyyy') : ''); ?></th>
                    <th><?= $row['subject']['name']; ?></th>
                    <th nowrap><?= ($row['practice'] == 1 ? 'вир. нав.' : 'теор. нав.'); ?></th>
                    <?php foreach ($row['timetable'] as $timetable) { ?>
                        <th><?= $timetable['lectComplete']; ?></th>
                        <?php
                    }
                    ?>
                    <th><?= $row['lectComplete']; ?></th>
                </tr>
                <?php
                $num++;
            }
        } ?>
        </tbody>
    </table>
</div>
