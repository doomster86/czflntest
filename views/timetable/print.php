<?php

require_once $baseUrl.'/Classes/PHPExcel.php';
$pExcel = new PHPExcel();

$table_id = $_GET["table_id"];
$grId = $_GET["group_id"];
$teacher_id = $_GET["teacher_id"];

$timetable = \app\models\TimetableParts::getListsCount($table_id);
$formatter = new \yii\i18n\Formatter;
$weeks = $timetable['count'];
$start = $timetable['start'];
$end = $timetable['end'];

$pExcel->setActiveSheetIndex(0);
$aSheet = $pExcel->getActiveSheet();

// Ориентация страницы и  размер листа
$aSheet->getPageSetup()
    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$aSheet->getPageSetup()
    ->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// Поля документа
$aSheet->getPageMargins()->setTop(1);
$aSheet->getPageMargins()->setRight(0.75);
$aSheet->getPageMargins()->setLeft(0.75);
$aSheet->getPageMargins()->setBottom(1);
// Название листа
$aSheet->setTitle('Розклад');

// Настройки шрифта
$pExcel->getDefaultStyle()->getFont()->setName('Arial');
$pExcel->getDefaultStyle()->getFont()->setSize(12);

$aSheet->getColumnDimension('A')->setWidth(25);
$aSheet->getColumnDimension('B')->setWidth(25);
$aSheet->getColumnDimension('C')->setWidth(25);
$aSheet->getColumnDimension('D')->setWidth(25);
$aSheet->getColumnDimension('E')->setWidth(25);
$aSheet->getColumnDimension('F')->setWidth(25);
$aSheet->getColumnDimension('G')->setWidth(25);

$aSheet->mergeCells('A1:G1');
$aSheet->getRowDimension('1')->setRowHeight(20);

if($grId){
    $groupName = \app\models\Groups::find()
        ->asArray()
        ->select('name')
        ->where(['=', 'id', $grId])
        ->one();
    $groupName = $groupName['name'];

    $aSheet->setCellValue('A1','Розклад занять для групи '.$groupName);
}

if($teacher_id){
    $teacherName = \app\models\User::find()
        ->asArray()
        ->select('firstname, middlename, lastname')
        ->where(['=', 'id', $teacher_id])
        ->one();
    $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

    $aSheet->setCellValue('A1','Розклад занять для викладача '.$teacherName);
}

$aSheet->mergeCells('A2:G2');
$aSheet->setCellValue('A2',$formatter->asDate($start, "dd.MM.yyyy").' - '.$formatter->asDate($end, "dd.MM.yyyy"));

// Создаем шапку таблички данных
$aSheet->mergeCells('A3:G3');
$aSheet->setCellValue('A3','');

$aSheet->setCellValue('A5','Понеділок');
$aSheet->setCellValue('B5','Вівторок');
$aSheet->setCellValue('C5','Середа');
$aSheet->setCellValue('D5','Четвер');
$aSheet->setCellValue('E5','П\'ятниця');
$aSheet->setCellValue('F5','Субота');
$aSheet->setCellValue('G5','Неділя');


$weeks = $weeks- 1; //т.к. отсчёт листов с нуля
for($i = 1; $i < $weeks; $i++) {
    $objClonedWorksheet = clone $pExcel->getSheetByName('Розклад');
    $objClonedWorksheet->setTitle('Розклад, сторінка '.($i+1));
    $pExcel->addSheet($objClonedWorksheet, $i);
}

for($i = 0; $i< $weeks; $i++) {
    $pExcel->setActiveSheetIndex($i);
    $aSheet = $pExcel->getActiveSheet();

    for($j = 0; $j<7; $j++) {
        if($start <= $end) {
            $day = $formatter->asDate($start, "l"); //текущий день недели
            $cellNum = 6;
            switch ($day) {
                case 'Monday':
                    $aSheet->setCellValue('A4',$formatter->asDate($start, "dd.MM.yyyy"));

                    if($teacher_id) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'teacher_id', $teacher_id])
                            ->all();
                    }

                    if($grId) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'group_id', $grId])
                            ->all();
                    }

                    foreach ($input_array as $cell) {
                        $y = $cell['y'];
                        $cellNum = $cellNum +$y;

                        $aSheet->getRowDimension($cellNum)->setRowHeight(160);

                        $corpsName = \app\models\Corps::find()
                            ->asArray()
                            ->select('corps_name')
                            ->where(['=', 'ID', $cell['corps_id']])
                            ->one();
                        $corpsName = $corpsName['corps_name'];

                        $audienceName = \app\models\Audience::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'ID', $cell['audience_id']])
                            ->one();
                        $audienceName = $audienceName['name'];

                        $teacherName = \app\models\User::find()
                            ->asArray()
                            ->select('firstname, middlename, lastname')
                            ->where(['=', 'id', $cell['teacher_id']])
                            ->one();
                        $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

                        $groupName = \app\models\Groups::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['group_id']])
                            ->one();
                        $groupName = $groupName['name'];

                        $subjName = \app\models\Subjects::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['subjects_id']])
                            ->one();
                        $subjName = $subjName['name'];

                        $aSheet->setCellValue('A'.$cellNum, 'Корпус: ' . $corpsName
                            ."\n\n".'Аудиторія: ' . $audienceName
                            ."\n\n".'Викладач: ' . $teacherName
                            ."\n\n".'Группа: ' . $groupName
                            ."\n\n".'Предмет: ' . $subjName
                        );

                        $pExcel->getActiveSheet()->getStyle('A'.$cellNum)->getAlignment()->setWrapText(true);

                        $cellNum = 6;
                    }

                    $start = $start + 86400;
                    $j=0;
                    break;
                case 'Tuesday':
                    $aSheet->setCellValue('B4',$formatter->asDate($start, "dd.MM.yyyy"));

                    if($teacher_id) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'teacher_id', $teacher_id])
                            ->all();
                    }

                    if($grId) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'group_id', $grId])
                            ->all();
                    }

                    foreach ($input_array as $cell) {
                        $y = $cell['y'];
                        $cellNum = $cellNum +$y;

                        $aSheet->getRowDimension($cellNum)->setRowHeight(160);

                        $corpsName = \app\models\Corps::find()
                            ->asArray()
                            ->select('corps_name')
                            ->where(['=', 'ID', $cell['corps_id']])
                            ->one();
                        $corpsName = $corpsName['corps_name'];

                        $audienceName = \app\models\Audience::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'ID', $cell['audience_id']])
                            ->one();
                        $audienceName = $audienceName['name'];

                        $teacherName = \app\models\User::find()
                            ->asArray()
                            ->select('firstname, middlename, lastname')
                            ->where(['=', 'id', $cell['teacher_id']])
                            ->one();
                        $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

                        $groupName = \app\models\Groups::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['group_id']])
                            ->one();
                        $groupName = $groupName['name'];

                        $subjName = \app\models\Subjects::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['subjects_id']])
                            ->one();
                        $subjName = $subjName['name'];

                        $aSheet->setCellValue('B'.$cellNum, 'Корпус: ' . $corpsName
                            ."\n\n".'Аудиторія: ' . $audienceName
                            ."\n\n".'Викладач: ' . $teacherName
                            ."\n\n".'Группа: ' . $groupName
                            ."\n\n".'Предмет: ' . $subjName
                        );
                        $pExcel->getActiveSheet()->getStyle('B'.$cellNum)->getAlignment()->setWrapText(true);

                        $cellNum = 6;
                    }

                    $start = $start + 86400;
                    $j=1;
                    break;
                case 'Wednesday':
                    $aSheet->setCellValue('C4',$formatter->asDate($start, "dd.MM.yyyy"));

                    if($teacher_id) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'teacher_id', $teacher_id])
                            ->all();
                    }

                    if($grId) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'group_id', $grId])
                            ->all();
                    }

                    foreach ($input_array as $cell) {
                        $y = $cell['y'];
                        $cellNum = $cellNum +$y;

                        $aSheet->getRowDimension($cellNum)->setRowHeight(160);

                        $corpsName = \app\models\Corps::find()
                            ->asArray()
                            ->select('corps_name')
                            ->where(['=', 'ID', $cell['corps_id']])
                            ->one();
                        $corpsName = $corpsName['corps_name'];

                        $audienceName = \app\models\Audience::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'ID', $cell['audience_id']])
                            ->one();
                        $audienceName = $audienceName['name'];

                        $teacherName = \app\models\User::find()
                            ->asArray()
                            ->select('firstname, middlename, lastname')
                            ->where(['=', 'id', $cell['teacher_id']])
                            ->one();
                        $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

                        $groupName = \app\models\Groups::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['group_id']])
                            ->one();
                        $groupName = $groupName['name'];

                        $subjName = \app\models\Subjects::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['subjects_id']])
                            ->one();
                        $subjName = $subjName['name'];

                        $aSheet->setCellValue('C'.$cellNum, 'Корпус: ' . $corpsName
                            ."\n\n".'Аудиторія: ' . $audienceName
                            ."\n\n".'Викладач: ' . $teacherName
                            ."\n\n".'Группа: ' . $groupName
                            ."\n\n".'Предмет: ' . $subjName
                        );
                        $pExcel->getActiveSheet()->getStyle('C'.$cellNum)->getAlignment()->setWrapText(true);

                        $cellNum = 6;
                    }

                    $start = $start + 86400;
                    $j=2;
                    break;
                case 'Thursday':
                    $aSheet->setCellValue('D4',$formatter->asDate($start, "dd.MM.yyyy"));

                    if($teacher_id) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'teacher_id', $teacher_id])
                            ->all();
                    }

                    if($grId) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'group_id', $grId])
                            ->all();
                    }

                    foreach ($input_array as $cell) {
                        $y = $cell['y'];
                        $cellNum = $cellNum +$y;

                        $aSheet->getRowDimension($cellNum)->setRowHeight(160);

                        $corpsName = \app\models\Corps::find()
                            ->asArray()
                            ->select('corps_name')
                            ->where(['=', 'ID', $cell['corps_id']])
                            ->one();
                        $corpsName = $corpsName['corps_name'];

                        $audienceName = \app\models\Audience::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'ID', $cell['audience_id']])
                            ->one();
                        $audienceName = $audienceName['name'];

                        $teacherName = \app\models\User::find()
                            ->asArray()
                            ->select('firstname, middlename, lastname')
                            ->where(['=', 'id', $cell['teacher_id']])
                            ->one();
                        $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

                        $groupName = \app\models\Groups::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['group_id']])
                            ->one();
                        $groupName = $groupName['name'];

                        $subjName = \app\models\Subjects::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['subjects_id']])
                            ->one();
                        $subjName = $subjName['name'];

                        $aSheet->setCellValue('D'.$cellNum, 'Корпус: ' . $corpsName
                            ."\n\n".'Аудиторія: ' . $audienceName
                            ."\n\n".'Викладач: ' . $teacherName
                            ."\n\n".'Группа: ' . $groupName
                            ."\n\n".'Предмет: ' . $subjName
                        );
                        $pExcel->getActiveSheet()->getStyle('D'.$cellNum)->getAlignment()->setWrapText(true);

                        $cellNum = 6;
                    }

                    $start = $start + 86400;
                    $j=3;
                    break;
                case 'Friday':
                    $aSheet->setCellValue('E4',$formatter->asDate($start, "dd.MM.yyyy"));

                    if($teacher_id) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'teacher_id', $teacher_id])
                            ->all();
                    }

                    if($grId) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'group_id', $grId])
                            ->all();
                    }

                    foreach ($input_array as $cell) {
                        $y = $cell['y'];
                        $cellNum = $cellNum +$y;

                        $aSheet->getRowDimension($cellNum)->setRowHeight(160);

                        $corpsName = \app\models\Corps::find()
                            ->asArray()
                            ->select('corps_name')
                            ->where(['=', 'ID', $cell['corps_id']])
                            ->one();
                        $corpsName = $corpsName['corps_name'];

                        $audienceName = \app\models\Audience::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'ID', $cell['audience_id']])
                            ->one();
                        $audienceName = $audienceName['name'];

                        $teacherName = \app\models\User::find()
                            ->asArray()
                            ->select('firstname, middlename, lastname')
                            ->where(['=', 'id', $cell['teacher_id']])
                            ->one();
                        $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

                        $groupName = \app\models\Groups::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['group_id']])
                            ->one();
                        $groupName = $groupName['name'];

                        $subjName = \app\models\Subjects::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['subjects_id']])
                            ->one();
                        $subjName = $subjName['name'];

                        $aSheet->setCellValue('E'.$cellNum, 'Корпус: ' . $corpsName
                            ."\n\n".'Аудиторія: ' . $audienceName
                            ."\n\n".'Викладач: ' . $teacherName
                            ."\n\n".'Группа: ' . $groupName
                            ."\n\n".'Предмет: ' . $subjName
                        );
                        $pExcel->getActiveSheet()->getStyle('E'.$cellNum)->getAlignment()->setWrapText(true);

                        $cellNum = 6;
                    }

                    $start = $start + 86400;
                    $j=4;
                    break;
                case 'Saturday':
                    $aSheet->setCellValue('F4',$formatter->asDate($start, "dd.MM.yyyy"));

                    if($teacher_id) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'teacher_id', $teacher_id])
                            ->all();
                    }

                    if($grId) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'group_id', $grId])
                            ->all();
                    }

                    foreach ($input_array as $cell) {
                        $y = $cell['y'];
                        $cellNum = $cellNum +$y;

                        $aSheet->getRowDimension($cellNum)->setRowHeight(160);

                        $corpsName = \app\models\Corps::find()
                            ->asArray()
                            ->select('corps_name')
                            ->where(['=', 'ID', $cell['corps_id']])
                            ->one();
                        $corpsName = $corpsName['corps_name'];

                        $audienceName = \app\models\Audience::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'ID', $cell['audience_id']])
                            ->one();
                        $audienceName = $audienceName['name'];

                        $teacherName = \app\models\User::find()
                            ->asArray()
                            ->select('firstname, middlename, lastname')
                            ->where(['=', 'id', $cell['teacher_id']])
                            ->one();
                        $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

                        $groupName = \app\models\Groups::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['group_id']])
                            ->one();
                        $groupName = $groupName['name'];

                        $subjName = \app\models\Subjects::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['subjects_id']])
                            ->one();
                        $subjName = $subjName['name'];

                        $aSheet->setCellValue('F'.$cellNum, 'Корпус: ' . $corpsName
                            ."\n\n".'Аудиторія: ' . $audienceName
                            ."\n\n".'Викладач: ' . $teacherName
                            ."\n\n".'Группа: ' . $groupName
                            ."\n\n".'Предмет: ' . $subjName
                        );
                        $pExcel->getActiveSheet()->getStyle('F'.$cellNum)->getAlignment()->setWrapText(true);

                        $cellNum = 6;
                    }

                    $start = $start + 86400;
                    $j=5;
                    break;
                case 'Sunday':
                    $aSheet->setCellValue('G4',$formatter->asDate($start, "dd.MM.yyyy"));

                    if($teacher_id) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'teacher_id', $teacher_id])
                            ->all();
                    }

                    if($grId) {
                        $input_array = \app\models\Timetable::find()
                            ->asArray()
                            ->where( [ '=', 'part_id', $table_id ] )
                            ->andWhere(['=', 'date', $start])
                            ->andWhere(['=', 'group_id', $grId])
                            ->all();
                    }

                    foreach ($input_array as $cell) {
                        $y = $cell['y'];
                        $cellNum = $cellNum +$y;

                        $aSheet->getRowDimension($cellNum)->setRowHeight(160);

                        $corpsName = \app\models\Corps::find()
                            ->asArray()
                            ->select('corps_name')
                            ->where(['=', 'ID', $cell['corps_id']])
                            ->one();
                        $corpsName = $corpsName['corps_name'];

                        $audienceName = \app\models\Audience::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'ID', $cell['audience_id']])
                            ->one();
                        $audienceName = $audienceName['name'];

                        $teacherName = \app\models\User::find()
                            ->asArray()
                            ->select('firstname, middlename, lastname')
                            ->where(['=', 'id', $cell['teacher_id']])
                            ->one();
                        $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

                        $groupName = \app\models\Groups::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['group_id']])
                            ->one();
                        $groupName = $groupName['name'];

                        $subjName = \app\models\Subjects::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'id', $cell['subjects_id']])
                            ->one();
                        $subjName = $subjName['name'];

                        $aSheet->setCellValue('G'.$cellNum, 'Корпус: ' . $corpsName
                            ."\n\n".'Аудиторія: ' . $audienceName
                            ."\n\n".'Викладач: ' . $teacherName
                            ."\n\n".'Группа: ' . $groupName
                            ."\n\n".'Предмет: ' . $subjName
                        );
                        $pExcel->getActiveSheet()->getStyle('G'.$cellNum)->getAlignment()->setWrapText(true);

                        $cellNum = 6;
                    }

                    $start = $start + 86400;
                    $j=6;
                    break;
            }
        }
    }
}

ob_end_clean();
date_default_timezone_set('europe/kiev');
//header('Content-Type:xlsx:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition:attachment;filename="simple.xlsx"');
//$objWriter = new PHPExcel_Writer_Excel2007($pExcel);

header('Content-Type:application/vnd.ms-excel');

if($grId){
    $groupName = \app\models\Groups::find()
        ->asArray()
        ->select('name')
        ->where(['=', 'id', $grId])
        ->one();
    $groupName = $groupName['name'];

    header('Content-Disposition:attachment;filename="Розклад '.$groupName.'.xls"');
}

if($teacher_id){
    $teacherName = \app\models\User::find()
        ->asArray()
        ->select('firstname, middlename, lastname')
        ->where(['=', 'id', $teacher_id])
        ->one();
    $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

    header('Content-Disposition:attachment;filename="Розклад '.$teacherName.'.xls"');
}

$objWriter = new PHPExcel_Writer_Excel5($pExcel);
$objWriter->save('php://output');