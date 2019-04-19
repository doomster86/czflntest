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

?>
    <script type="application/javascript">
        function print() {
            var divToPrint=document.getElementById("printtable");
            var newWin= window.open("");
            var myStyle = document.getElementsByTagName('head')[0];
            newWin.document.write(myStyle.outerHTML);
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        }
    </script>

<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
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
                <?= Html::button('Роздрукувати', ['class' => 'btn btn-success', 'onclick' => 'print()']); ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php

echo Timetable::renderPrintTable($date_start, $date_end, $g);

?>