<?php
/**
 * Created by PhpStorm.
 * User: seryo
 * Date: 29.12.2018
 * Time: 8:45
 */
$prof_id = $RnpsArray['prof_id'];
$courses = count($RnpSubjectsArray);
$modules = count($weeksArray);
$nakazy = count($nakazArray);
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
                    <th rowspan="2">№ <p style="white-space: nowrap">з/п</p></th>
                    <th rowspan="2">Навчальні предмети</th>
                    <th rowspan="2">Кількість годин (заплановано)</th>
                    <th colspan="<?php echo $modules; ?>" id="weeks">Кількість тижнів</th>
                    <th rowspan="2">Всього за категорію (фактично)</th>
                    <?php
                    for ($i=0; $i < $nakazy; $i++) {
                       ?>
                    <th rowspan="2" class="nakaz"><textarea rows="3" name="nakaz[]"><?php echo $nakazArray[$i]['title'];?></textarea>
                    <?php
                    }
                    ?>
                    </th>
                    <th rowspan="2"></th>
                </tr>
                <tr>
                    <?php
                    for ($i = 0; $i < $modules; $i++) {
                        ?>
                        <th class="week">
                            <?php if ($i) { ?>
                                <button class="btn btn-danger form-control" style="margin-bottom: 9px;" type="button" data-toggle="modal" data-target="#module_del_<?php echo $i; ?>"><i
                                            class="glyphicon glyphicon-remove"></i> Видалити
                                </button>
                            <?php } ?>
                            <input type="number" name="weeks[]" value="<?php echo $weeksArray[$i]['column_rep'];?>" class="form-control" min="1" required>
                        </th>
                        <?php
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $teacher = 0;
                for ($k = 0; $k < $courses; $k++) {
                    $fact = 0;
                    ?>
                    <tr>
                        <td style="vertical-align: middle;font-weight: bold;text-align: center;"><?php echo $k + 1; ?></td>
                        <td><input type="text" name="courses[]" value="<?php echo $RnpSubjectsArray[$k]['title']; ?>" class="form-control" required>
                            <input type="hidden" name="ids[]" value="<?php echo $RnpSubjectsArray[$k]['ID']; ?>" class="form-control" required>
                        </td>
                        <td><input type="number" name="zaplan[]" value="<?php echo $RnpSubjectsArray[$k]['plan_all']; ?>" class="form-control" min="0" required></td>
                        <?php
                        for ($j = 0; $j < $modules; $j++) {
                            foreach ($modulesArray as $module) {
                                if ($module['subject_id'] == $RnpSubjectsArray[$k]['ID'] && $module['column_num'] == $j) {
                                    $value = $module['column_plan'];
                                    $fact += $module['column_plan'] * $module['column_rep'];
                                }
                            }
                            ?>
                            <td class="module"><input type="number" name="modules[<?php echo $k ?>][<?php echo $j ?>]" value="<?php echo $value; ?>" class="form-control" min="0" style="min-width: 70px;" required>
                            </td>
                            <?php
                        }
                        ?>
                        <td><input type="number" value="<?php echo $fact; ?>" class="form-control" disabled></td>
                        <?php
                        for ($p=0; $p < $nakazy; $p++) {
                            foreach ($teachersArray as $teacher) {
                                if ($teacher['subject_id'] == $RnpSubjectsArray[$k]['ID'] && $teacher['column_num'] == $p) {
                                    $value = $teacher['teacher_id'];
                                }
                            }
                        ?>
                            <td class="teacher"><select class="form-control" name="teacher[<?php echo $k ?>][<?php echo $p ?>]" required>
                                    <option value="">Оберіть викладача</option>
                                    <?php
                                    foreach ($UsersArray as $user) {
                                        echo '<option value="' . $user['id'] . '"'.($value==$user['id']?' selected':'').'>' . $user['lastname'] . ' ' . mb_substr($user['firstname'], 0, 1) . '.' . mb_substr($user['middlename'], 0, 1) . '.' . '</option>';
                                    }
                                    ?>
                                </select></td>
                            <?php
                            $teacher++;
                            }
                            ?>
                        <td>
                            <?php if ($k) { ?>
                            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#course_del_<?php echo $RnpSubjectsArray[$k]['ID']; ?>"><i
                                        class="glyphicon glyphicon-remove"></i> Видалити
                            </button>
                            <?php } ?>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="course_del_<?php echo $RnpSubjectsArray[$k]['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="exampleModalLongTitle">Видалення предмету</h4>
                                </div>
                                <div class="modal-body">
                                    Ви дійсно хочете видалити предмет?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                                    <button type="submit" name="deletesubject" value="<?php echo $RnpSubjectsArray[$k]['ID']; ?>" class="btn btn-primary">Видалити</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php
        for ($i = 0; $i < $modules; $i++) {
            ?>
            <!-- Modal -->
            <div class="modal fade" id="module_del_<?php echo $i; ?>" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="exampleModalLongTitle">Видалення тижня</h4>
                        </div>
                        <div class="modal-body">
                            Ви дійсно хочете видалити тиждень?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                            <button type="submit" name="deletemodule" value="<?php echo $i; ?>" class="btn btn-primary">
                                Видалити
                            </button>
                        </div>
                    </div>
                </div>
            </div>                <?php
        }
        ?>
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
        <div class="form-group">
            <button class="btn btn-danger" type="button" data-toggle="modal"
                    data-target="#deletetable"><i
                        class="glyphicon glyphicon-remove"></i> Видалити таблицю
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="deletetable" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLongTitle">Видалення таблиці</h4>
                    </div>
                    <div class="modal-body">
                        Ви збираетесь видалити таблицю РНП. Підтвердити?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                        <button type="submit" name="deletetable" value="deletetable" class="btn btn-primary">Видалити</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <table>
        <thead class="copy-fields-week hide">
        <tr>
            <th class="week">
                <button class="btn btn-danger form-control remove-module" style="margin-bottom: 9px;" type="button"><i
                            class="glyphicon glyphicon-remove"></i> Видалити
                </button>
                <input type="number" name="weeks[]" class="form-control" min="1" required>
            </th>
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
            <?php
            for ($p=0; $p < $nakazy; $p++) {
                ?>
                <td class="teacher"><select class="form-control" name="teacher[][<?php echo $p ?>]" required>
                        <option value="">Оберіть викладача</option>
                        <?php
                        foreach ($UsersArray as $user) {
                            echo '<option value="' . $user['id'] . '">' . $user['lastname'] . ' ' . mb_substr($user['firstname'], 0, 1) . '.' . mb_substr($user['middlename'], 0, 1) . '.' . '</option>';
                        }
                        ?>
                    </select></td>
                <?php
            }
            ?>
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
            <td class="module"><input type="number" name="modules[]" class="form-control" style="min-width: 70px;" min="0" required></td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead class="copy-fields-nakaz hide">
        <tr>
            <th rowspan="2" class="nakaz"><textarea rows="3" name="nakaz[]">Зміни 1 №***-Н від **.**.**  до наказу №***-Н від **.**.**</textarea>
            </th>
        </tr>
        </thead>
        <tbody class="copy-fields-teacher hide">
        <tr>
            <td class="teacher"><select class="form-control" name="teacher[][]" required>
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
