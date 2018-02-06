<?php

require_once $baseUrl.'/Classes/PHPExcel.php';
$pExcel = new PHPExcel();

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

$aSheet->mergeCells('A1:E1');
$aSheet->getRowDimension('1')->setRowHeight(20);
$aSheet->setCellValue('A1','Розклад занять для групи');
$aSheet->mergeCells('A2:E2');
$aSheet->setCellValue('A2','01.02.18 - 28.02.18');

// Создаем шапку таблички данных
$aSheet->setCellValue('A3','');
$aSheet->setCellValue('B3','');
$aSheet->setCellValue('C3','');
$aSheet->setCellValue('D3','');
$aSheet->setCellValue('E3','');
$aSheet->setCellValue('F3','');
$aSheet->setCellValue('G3','');

$aSheet->setCellValue('A4','Понеділок');
$aSheet->setCellValue('B4','Вівторок');
$aSheet->setCellValue('C4','Середа');
$aSheet->setCellValue('D4','Четвер');
$aSheet->setCellValue('E4','П\'ятниця');
$aSheet->setCellValue('F4','Субота');
$aSheet->setCellValue('G4','Неділя');

ob_end_clean();
date_default_timezone_set('europe/kiev');
//header('Content-Type:xlsx:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition:attachment;filename="simple.xlsx"');
//$objWriter = new PHPExcel_Writer_Excel2007($pExcel);

header('Content-Type:application/vnd.ms-excel');
header('Content-Disposition:attachment;filename="simple.xls"');
$objWriter = new PHPExcel_Writer_Excel5($pExcel);
$objWriter->save('php://output');