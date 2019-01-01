<?php
/**
 * Created by PhpStorm.
 * User: seryo
 * Date: 29.12.2018
 * Time: 8:45
 */
$prof_id = $RnpsArray['prof_id'];
$courses = count($RnpSubjectsArray);
$modules = count($RnpSubjectsArray);
?>
<div class="form-group">
    <form action="" method="post">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
        <input type="hidden" name="modules_count" class="modules" value="<?php echo $modules; ?>"/>
        <input type="hidden" name="courses_count" class="courses" value="<?php echo $courses; ?>"/>
        <input type="hidden" name="prof_id" value="<?php echo $prof_id; ?>"/>
        <div style="overflow-x:auto;">
            <table class="table table-bordered" id="rnp_table">
                <thead>
                <tr>
                    <th rowspan="2">№ з/п</th>
                    <th rowspan="2">Навчальні предмети</th>
                    <th rowspan="2">Кількість годин (заплановано)</th>
                    <th colspan="<?php echo $modules; ?>" id="weeks">Кількість тижнів</th>
                    <th rowspan="2">Всього за категорію (фактично)</th>
                    <th rowspan="2" class="nakaz"><textarea rows="3" name="text">Наказ про педнавантаження №***-Н від **.**.**</textarea>
                    </th>
                </tr>
                <tr>
                    <?php
                    for ($i = 0; $i < $modules; $i++) {
                        ?>
                        <th class="week"><input type="number" name="weeks[]" class="form-control" min="1" required></th>
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
                        <td><input type="text" name="courses[]" value="<?php echo $RnpSubjectsArray[$k]['title']; ?>" class="form-control" required></td>
                        <td><input type="number" name="zaplan[]" value="<?php echo $RnpSubjectsArray[$k]['plan_all']; ?>" class="form-control" min="0" required></td>
                        <?php
                        for ($j = 0; $j < $modules; $j++) {
                            ?>
                            <td class="module"><input type="number" name="modules[<?php echo $k ?>][<?php echo $j ?>]" class="form-control" min="0" style="min-width: 70px;" required>
                            </td>
                            <?php
                        }
                        ?>
                        <td>?</td>
                        <td class="teacher"><select class="form-control">
                                <option value="">Оберіть викладача</option>
                                <?php
                                foreach ($UsersArray as $user) {
                                    echo '<option value="' . $user['id'] . '">' . $user['lastname'] . ' ' . mb_substr($user['firstname'], 0, 1) . '.' . mb_substr($user['middlename'], 0, 1) . '.' . '</option>';
                                }
                                ?>
                            </select></td>
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
            <th class="week"><input type="number" name="weeks[]" class="form-control" min="1" required></th>
        </tr>
        </thead>
        <tbody class="copy-fields-course hide">
        <tr>
            <td class="index" style="vertical-align: middle;font-weight: bold;text-align: center;"></td>
            <td><input type="text" name="courses[]" class="form-control" required></td>
            <td><input type="number" name="zaplan[]" class="form-control" min="0" required></td>
            <?php
            for ($i = 0; $i < $modules; $i++) {
                ?>
                <td class="module"><input type="number" name="modules[][<?php echo $i ?>]" class="form-control" style="min-width: 70px;" min="0" required></td>
                <?php
            }
            ?>
            <td>?</td>
            <td class="teacher"><select class="form-control">
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
    <table>
        <tbody class="copy-fields-module hide">
        <tr>
            <td class="module"><input type="number" name="modules[]" class="form-control" style="min-width: 70px;" min="0" required></td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead class="copy-fields-nakaz hide">
        <tr>
            <th rowspan="2" class="nakaz"><textarea rows="3" name="text">Зміни 1 №***-Н від **.**.**  до наказу №***-Н від **.**.**</textarea>
            </th>
        </tr>
        </thead>
        <tbody class="copy-fields-teacher hide">
        <tr>
            <td class="teacher"><select class="form-control">
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
