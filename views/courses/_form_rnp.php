<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.12.2018
 * Time: 12:37
 */

$courses = 1;
if (!empty($request['modules_count'])) {
    $modules = $request['modules_count'];
}
if (!empty($request['courses_count'])) {
    $courses = $request['courses_count'];
}
?>
<div class="form-group">
<form action="" method="post">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
    <input type="hidden" name="modules_count" class="modules" value="<?php echo $modules; ?>"/>
    <input type="hidden" name="courses_count" class="courses" value="<?php echo $courses; ?>"/>
    <input type="hidden" name="prof_id" value="<?php echo $prof_id; ?>"/>
    <div style="overflow-x:auto;" class="sticky-table sticky-ltr-cells">
        <table class="table table-bordered" id="rnp_table">
            <thead>
            <tr>
                <th rowspan="2">№ <p style="white-space: nowrap">з/п</p></th>
                <th rowspan="2" class="sticky-cell">Навчальні предмети</th>
                <th rowspan="2"><p>Кількість годин (заплановано)</p>Нотатка</th>
                <th colspan="<?php echo $modules; ?>" id="weeks">Кількість тижнів</th>
                <th rowspan="2">Всього за категорію (фактично)</th>
                <th rowspan="2" class="nakaz sticky-cell-opposite"><textarea rows="3" name="nakaz[]">Наказ про педнавантаження №***-Н від **.**.**</textarea>
                </th>
                <th rowspan="2"></th>
            </tr>
            <tr>
                <?php
                for ($i = 0; $i < $modules; $i++) {
                    ?>
                    <th class="week"><input type="number" name="weeks[]" class="form-control form-group" min="1" required><input type="text" name="text[]" value="" class="form-control"></th>
                    <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            for ($k = 0; $k < $courses; $k++) {
                ?>
                <tr>
                    <td style="vertical-align: middle;font-weight: bold;text-align: center;"><?php echo $k + 1; ?></td>
                    <td class="sticky-cell"><input style="width: 200px;" type="text" name="courses[]" class="form-control" required></td>
                    <td><input type="number" name="zaplan[]" class="form-control" min="0" required></td>
                    <?php
                    for ($j = 0; $j < $modules; $j++) {
                        ?>
                        <td class="module"><input type="number" name="modules[<?php echo $k ?>][<?php echo $j ?>]" class="form-control" min="1" style="min-width: 57px;">
                        </td>
                        <?php
                    }
                    ?>
                    <td>?</td>
                    <td class="teacher sticky-cell-opposite"><select class="form-control" name="teacher[0][0]" required>
                            <option value="">Оберіть викладача</option>
                            <?php
                            foreach ($UsersArray as $user) {
                                echo '<option value="' . $user['id'] . '">' . $user['lastname'] . ' ' . mb_substr($user['firstname'], 0, 1) . '.' . mb_substr($user['middlename'], 0, 1) . '.' . '</option>';
                            }
                            ?>
                        </select></td>
                    <td></td>
                </tr>
            <?php } ?>
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
        <button class="btn btn-success add-more-nakaz" type="button"><i class="glyphicon glyphicon-plus"></i> Додати
            наказ
        </button>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-info">Обновити</button>
    </div>
</form>
<table>
    <thead class="copy-fields-week hide">
    <tr>
        <th class="week">
            <button class="btn btn-danger form-control remove-module" style="margin-bottom: 9px;" type="button"><i
                        class="glyphicon glyphicon-remove"></i>
            </button>
            <input type="number" name="weeks[]" class="form-control form-group" min="1" required><input type="text" name="text[]" value="" class="form-control">
        </th>
    </tr>
    </thead>
    <tbody class="copy-fields-course hide">
    <tr>
        <td class="index" style="vertical-align: middle;font-weight: bold;text-align: center;"></td>
        <td class="sticky-cell"><input style="width: 200px;" type="text" name="courses[]" class="form-control" required></td>
        <td><input type="number" name="zaplan[]" class="form-control" min="0" required></td>
        <?php
        for ($i = 0; $i < $modules; $i++) {
            ?>
            <td class="module"><input type="number" name="modules[][<?php echo $i ?>]" class="form-control" style="min-width: 57px;" min="1"></td>
            <?php
        }
        ?>
        <td>?</td>
        <td class="teacher sticky-cell-opposite"><select class="form-control" required>
                <option value="">Оберіть викладача</option>
                <?php
                foreach ($UsersArray as $user) {
                    echo '<option value="' . $user['id'] . '">' . $user['lastname'] . ' ' . mb_substr($user['firstname'], 0, 1) . '.' . mb_substr($user['middlename'], 0, 1) . '.' . '</option>';
                }
                ?>
            </select></td>
        <td>
            <button class="btn btn-danger remove-course" type="button"><i
                        class="glyphicon glyphicon-remove"></i> Видалити
            </button>
        </td>
    </tr>
    </tbody>
</table>
<table>
    <tbody class="copy-fields-module hide">
    <tr>
        <td class="module"><input type="number" name="modules[]" class="form-control" style="min-width: 57px;" min="1"></td>
    </tr>
    </tbody>
</table>
<table>
    <thead class="copy-fields-nakaz hide">
    <tr>
        <th rowspan="2" class="nakaz sticky-cell-opposite">
            <button class="btn btn-danger form-control remove-nakaz" style="margin-bottom: 9px;" type="button"><i
                        class="glyphicon glyphicon-remove"></i> Видалити
            </button>
            <textarea rows="3" name="nakaz[]">Зміни 1 №***-Н від **.**.**  до наказу №***-Н від **.**.**</textarea>
        </th>
    </tr>
    </thead>
    <tbody class="copy-fields-teacher hide">
    <tr>
        <td class="teacher sticky-cell-opposite"><select class="form-control" name="modules[]" required>
                <option value="">Оберіть викладача</option>
                <?php
                foreach ($UsersArray as $user) {
                    echo '<option value="' . $user['id'] . '">' . $user['lastname'] . ' ' . mb_substr($user['firstname'], 0, 1) . '.' . mb_substr($user['middlename'], 0, 1) . '.' . '</option>';
                }
                ?>
            </select></td>
    </tr>
    </tbody>
</table>
</div>