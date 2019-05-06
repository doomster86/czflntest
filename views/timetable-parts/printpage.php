<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.02.2019
 * Time: 11:09
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TimetableParts;
use app\models\Timetable;
$formatter = new \yii\i18n\Formatter();
?>
    <div class="hidden-print">

        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group week-selector">
                    <label for="week">Виберіть тиждень</label>
                    <select class="form-control" id="week" name="week" required>
                        <option value="">Номер тижня року</option>
                        <?php
                        for ($i = 1; $i <= 52; $i++) {
                            $week = (!empty($request['week'])?$request['week']:1);
                            ?>
                            <option value="<?php echo $i; ?>"<?php echo ($week == $i?' selected':'');?>><?php echo $i; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="group">Виберіть групу</label>
                    <select class="form-control" id="group" name="group">
                        <option value="">Всі групи</option>
                        <?php
                        foreach ($groups as $group) {
                            $g = (!empty($request['group'])?$request['group']:0);
                            ?>
                            <option value="<?php echo $group['ID']; ?>"<?php echo ($g == $group['ID']?' selected':'');?>><?php echo $group['name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="form-group">
                    <?= Html::button('Роздрукувати', ['class' => 'btn btn-success hidden-print', 'onclick' => 'print()']); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$j = 0;
if ($g) {
    $Group = \app\models\Groups::find()
        ->asArray()
        ->where( [ '=', 'ID', $g ] )
        ->one();
    $timetable = Timetable::renderPrintTable($date_start, $date_end, $g);
    if ($timetable) {
        ?>
        <div class="row" style="font-size: 15px;">
            <div class="col-xs-8 col-sm-8 col-md-8"></div>
            <div class="col-xs-4 col-md-4">
                <p>ЗАТВЕРДЖУЮ</p>
                <p class="dolzh-append" style="display: none;"><textarea></textarea><br/><button class="btn dolzh-save" type="button">Зберегти</button></p>
                <p class="dolzh">Директор Харківського міського центру ПТО ДСЗ</p>
                <p class="initial-append" style="display: none;"><textarea></textarea><br/><button class="btn initial-save" type="button">Зберегти</button></p>
                <p class="initial">______________ Р.А Кір'янов</p>
            </div>
        </div>
        <div class="row" style="margin-top: 20px; font-weight: bold; font-size: 17px;" >
            <p align="center">РОЗКЛАД ЗАНЯТЬ<br/>
                слухачів група <?php echo $Group['name'] ?><br/>
                на тиждень з
            </p>
        </div>
        <?php
        echo $timetable;
    }
} else {
    foreach ($groups as $group) {
        $timetable = Timetable::renderPrintTable($date_start, $date_end, $group['ID']);
        if ($timetable) {
            $Group = \app\models\Groups::find()
                ->asArray()
                ->where(['=', 'ID', $group['ID']])
                ->one();
            $Course = \app\models\Courses::find()
                ->asArray()
                ->where(['=', 'ID', $Group['course']])
                ->one();
            ?>
            <div class="row" style="font-size: 15px;">
                <div class="col-xs-8 col-sm-8 col-md-8"></div>
                <div class="col-xs-4 col-md-4">
                    <p>ЗАТВЕРДЖУЮ</p>
                    <p class="dolzh-append" style="display: none;"><textarea></textarea><br/>
                        <button class="btn dolzh-save" type="button">Зберегти</button>
                    </p>
                    <p class="dolzh">Директор Харківського міського центру ПТО ДСЗ</p>
                    <p class="initial-append" style="display: none;"><textarea></textarea><br/>
                        <button class="btn initial-save" type="button">Зберегти</button>
                    </p>
                    <p class="initial">______________ Р.А Кір'янов</p>
                </div>
            </div>
            <div class="row" style="margin-top: 20px; font-weight: bold; font-size: 17px;">
                <p align="center">РОЗКЛАД ЗАНЯТЬ<br/>
                    слухачів група <?php echo $Group['name']; ?><br/>
                    на тиждень з <?php echo $formatter->asDate($date_start, 'dd.MM.yyyy'); ?> по <?php echo $formatter->asDate($date_end, 'dd.MM.yyyy'); ?>
                </p>
            </div>
            <div class="row" style="font-size: 17px; margin-left: 40px;">
                <p>Професія: "<b><?php echo $Course['name']; ?></b>",<br/>
                    Термін навчання: <b>з <?php echo $formatter->asDate($Group['date_start'], 'dd.MM.yyyy'); ?> по <?php echo $formatter->asDate($Group['date_end'], 'dd.MM.yyyy'); ?></b>
            </div>
            <?php
            echo $timetable;
            $j = 1;
        }
    }
}

if (!empty($timetable) || $j) {
    ?>

    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6" align="center">
            <p id="footer-1-append" style="display: none;"><textarea></textarea><br/><button class="btn" type="button" id="footer-1-save">Зберегти</button></p>
            <p id="footer-1">Текст блока «Подвал 1»</p>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6" align="center">
            <p id="footer-2-append" style="display: none;"><textarea></textarea><br/><button class="btn" type="button" id="footer-2-save">Зберегти</button></p>
            <p id="footer-2">Текст блока «Подвал 2»</p>
        </div>
    </div>

<?php }