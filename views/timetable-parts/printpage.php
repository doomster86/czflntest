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

?>
    <script type="application/javascript">
        function print() {
            var prtContent = document.getElementById("w0");
            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
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

$tableID = new TimetableParts();

$tableID = $tableID->find()
    ->asArray()
    ->select(['id'])
    ->where(['>=', 'datestart', $date_start]) // datestart <= $currentDate
    ->andWhere(['>=', 'dateend', $date_end])// dateend >= $currentDate
    ->one();
print_r($tableID);
$tableID = $tableID['id'];
print_r($tableID);
?>