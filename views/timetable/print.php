<?php

require_once $baseUrl.'/Classes/PHPExcel.php';
$pExcel = new PHPExcel();

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
$aSheet->setCellValue('A1','Розклад занять для групи '.$table_id);
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

/*
 * даты необходимо выводить для всех дней
 * а занятия, только для дней этого месяца
 */
for($i = 0; $i< $weeks; $i++) {
    $pExcel->setActiveSheetIndex($i);
    $aSheet = $pExcel->getActiveSheet();

    $day = $formatter->asDate($start, "l"); //текущий день недели
    for($j=0; $j<7; $j++) {
        if($start <= $end) {
            switch ($day) {
                case 'Monday':
                    $aSheet->setCellValue('A4',$formatter->asDate($start, "dd.MM.yyyy"));
                    $start = $start + 86400;
                    break;
                case 'Tuesday':
                    $aSheet->setCellValue('B4',$formatter->asDate($start, "dd.MM.yyyy"));
                    $start = $start + 86400;
                    break;
                case 'Wednesday':
                    $aSheet->setCellValue('C4',$formatter->asDate($start, "dd.MM.yyyy"));
                    $start = $start + 86400;
                    break;
                case 'Thursday':
                    $aSheet->setCellValue('D4',$formatter->asDate($start, "dd.MM.yyyy"));
                    $start = $start + 86400;
                    break;
                case 'Friday':
                    $aSheet->setCellValue('E4',$formatter->asDate($start, "dd.MM.yyyy"));
                    $start = $start + 86400;
                    break;
                case 'Saturday':
                    $aSheet->setCellValue('F4',$formatter->asDate($start, "dd.MM.yyyy"));
                    $start = $start + 86400;
                    break;
                case 'Sunday':
                    $aSheet->setCellValue('G4',$formatter->asDate($start, "dd.MM.yyyy"));
                    $start = $start + 86400;
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
header('Content-Disposition:attachment;filename="розклад.xls"');
$objWriter = new PHPExcel_Writer_Excel5($pExcel);
$objWriter->save('php://output');