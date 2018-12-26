<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.12.2018
 * Time: 12:37
 */
if (empty($request['modules'])) {
    $modules = $_POST['modules'];
} else {
    $modules = $request['modules'];
}
?>
<form action="" method="post">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
    <input type="hidden" name="modules" value="<?php echo $request['modules']; ?>"/>
    <div style="overflow-x:auto;">
        <table class="table table-bordered" id="rnp_table">
            <thead>
            <tr>
                <th rowspan="2">№ з/п</th>
                <th rowspan="2">Навчальні предмети</th>
                <th rowspan="2">Кількість годин (заплановано)</th>
                <th colspan="<?php echo $modules; ?>" id="weeks">Кількість тижнів</th>
                <th rowspan="2">Всього за категорію (фактично)</th>
            </tr>
            <tr>
                <?php
                for ($i = 0; $i < $modules; $i++) {
                    ?>
                    <th class="week"><input type="number" class="form-control" min="1"></th>
                    <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="vertical-align: middle;font-weight: bold;text-align: center;">1</td>
                <td><input type="text" class="form-control"></td>
                <td><input type="number" class="form-control"></td>
                <?php
                for ($i = 0; $i < $modules; $i++) {
                    ?>
                    <td class="module"><input type="number" class="form-control" min="0" style="min-width: 70px;"></td>
                    <?php
                }
                ?>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <button class="btn btn-success add-more-course" type="button"><i class="glyphicon glyphicon-plus"></i> Додати
            предмет
        </button>
    </div>
    <div class="form-group">
        <button class="btn btn-success add-more-module" type="button"><i class="glyphicon glyphicon-plus"></i> Додати
            тиждень
        </button>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-info">Обновити</button>
    </div>

    <table>
        <thead class="copy-fields-week hide">
        <tr>
            <th class="week"><input type="number" class="form-control" min="1"></th>
        </tr>
        </thead>
        <tbody class="copy-fields-course hide">
        <tr>
            <td class="index" style="vertical-align: middle;font-weight: bold;text-align: center;"></td>
            <td><input type="text" class="form-control"></td>
            <td><input type="number" class="form-control"></td>
            <?php
            for ($i = 0; $i < $modules; $i++) {
                ?>
                <td class="module"><input type="number" class="form-control" style="min-width: 70px;"></td>
                <?php
            }
            ?>
        </tr>
        </tbody>
    </table>
    <table>
        <tbody class="copy-fields-module hide">
        <tr>
            <td class="module"><input type="number" class="form-control" style="min-width: 70px;"></td>
        </tr>
        </tbody>
    </table>
</form>
