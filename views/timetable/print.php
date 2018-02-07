<?php

require_once $baseUrl.'/Classes/PHPExcel.php';
$pExcel = new PHPExcel();

$timetable = \app\models\TimetableParts::getListsCount($table_id);
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
    $aSheet->setCellValue('A2',$start.' - '.$end);

// Создаем шапку таблички данных
    $aSheet->mergeCells('A3:G3');
    $aSheet->setCellValue('A3','');

    $aSheet->setCellValue('A4','Понеділок');
    $aSheet->setCellValue('B4','Вівторок');
    $aSheet->setCellValue('C4','Середа');
    $aSheet->setCellValue('D4','Четвер');
    $aSheet->setCellValue('E4','П\'ятниця');
    $aSheet->setCellValue('F4','Субота');
    $aSheet->setCellValue('G4','Неділя');


    $weeks = $weeks- 1; //т.к. отсчёт листов с нуля
    for($i = 1; $i < $weeks; $i++) {
        $objClonedWorksheet = clone $pExcel->getSheetByName('Розклад');
        $objClonedWorksheet->setTitle('Розклад, сторінка '.($i+1));
        $pExcel->addSheet($objClonedWorksheet, $i);
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