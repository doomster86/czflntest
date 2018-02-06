<?php

require_once $baseUrl.'/Classes/PHPExcel.php';
$pExcel = new PHPExcel();

$pExcel->setActiveSheetIndex(0);
$aSheet = $pExcel->getActiveSheet();

// Ориентация страницы и  размер листа
$aSheet->getPageSetup()
    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$aSheet->getPageSetup()
    ->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
// Поля документа
$aSheet->getPageMargins()->setTop(1);
$aSheet->getPageMargins()->setRight(0.75);
$aSheet->getPageMargins()->setLeft(0.75);
$aSheet->getPageMargins()->setBottom(1);
// Название листа
$aSheet->setTitle('Прайс-лист');
// Шапка и футер (при печати)
$aSheet->getHeaderFooter()
    ->setOddHeader('&CТД ТИНКО: прайс-лист');
$aSheet->getHeaderFooter()
    ->setOddFooter('&L&B'.$aSheet->getTitle().'&RСтраница &P из &N');
// Настройки шрифта
$pExcel->getDefaultStyle()->getFont()->setName('Arial');
$pExcel->getDefaultStyle()->getFont()->setSize(8);

$aSheet->getColumnDimension('A')->setWidth(3);
$aSheet->getColumnDimension('B')->setWidth(7);
$aSheet->getColumnDimension('C')->setWidth(20);
$aSheet->getColumnDimension('D')->setWidth(40);
$aSheet->getColumnDimension('E')->setWidth(10);

$aSheet->mergeCells('A1:E1');
$aSheet->getRowDimension('1')->setRowHeight(20);
$aSheet->setCellValue('A1','ТД ТИНКО');
$aSheet->mergeCells('A2:E2');
$aSheet->setCellValue('A2','Поставка технических средств безопасности');
$aSheet->mergeCells('A4:C4');
$aSheet->setCellValue('A4','Дата создания прайс-листа');

// Записываем данные в ячейку
$date = date('d-m-Y');
$aSheet->setCellValue('D4',$date);
// Устанавливает формат данных в ячейке (дата вида дд-мм-гггг)
$aSheet->getStyle('D4')->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

// Создаем шапку таблички данных
$aSheet->setCellValue('A6','№');
$aSheet->setCellValue('B6','Код');
$aSheet->setCellValue('C6','Наименование');
$aSheet->setCellValue('D6','Описание');
$aSheet->setCellValue('E6','Цена');

// массив стилей
$style_wrap = array(
    // рамки
    'borders'=>array(
        // внешняя рамка
        'outline' => array(
            'style'=>PHPExcel_Style_Border::BORDER_THICK,
            'color' => array(
                'rgb'=>'006464'
            )
        ),
        // внутренняя
        'allborders'=>array(
            'style'=>PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb'=>'CCCCCC'
            )
        )
    )
);

$aSheet->getStyle('A1:F'.($i+5))->applyFromArray($style_wrap);

// Стили для верхней надписи (первая строка)
$style_header = array(
    // Шрифт
    'font'=>array(
        'bold' => true,
        'name' => 'Times New Roman',
        'size' => 15,
        'color'=>array(
            'rgb' => '006464'
        )
    ),
    // Выравнивание
    'alignment' => array(
        'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
    ),
    // Заполнение цветом
    'fill' => array(
        'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
        'color'=>array(
            'rgb' => '99CCCC'
        )
    ),
    'borders'=>array(
        'bottom'=>array(
            'style'=>PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb'=>'006464'
            )
        )
    )
);
$aSheet->getStyle('A1:E1')->applyFromArray($style_header);

// Стили для слогана компании (вторая строка)
$style_slogan = array(
    // шрифт
    'font'=>array(
        'bold' => true,
        'italic' => true,
        'name' => 'Times New Roman',
        'size' => 12,
        'color'=>array(
            'rgb' => '006464'
        )
    ),
    // выравнивание
    'alignment' => array(
        'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
    ),
    // заполнение цветом
    'fill' => array(
        'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
        'color'=>array(
            'rgb' => '99CCCC'
        )
    ),
    //рамки
    'borders' => array(
        'bottom' => array(
            'style'=>PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb'=>'006464'
            )
        )
    )
);
$aSheet->getStyle('A2:E2')->applyFromArray($style_slogan);

// Стили для текта возле даты
$style_tdate = array(
    // выравнивание
    'alignment' => array(
        'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
    ),
    // заполнение цветом
    'fill' => array(
        'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
        'color'=>array(
            'rgb' => 'EEEEEE'
        )
    ),
    // рамки
    'borders' => array(
        'right' => array(
            'style'=>PHPExcel_Style_Border::BORDER_NONE
        )
    )
);
$aSheet->getStyle('A4:D4')->applyFromArray($style_tdate);

// Стили для даты
$style_date = array(
    // заполнение цветом
    'fill' => array(
        'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
        'color'=>array(
            'rgb' => 'EEEEEE'
        )
    ),
    // рамки
    'borders' => array(
        'left' => array(
            'style'=>PHPExcel_Style_Border::BORDER_NONE
        )
    ),
);
$aSheet->getStyle('E4')->applyFromArray($style_date);

// Стили для шапки таблицы (шестая строка)
$style_hprice = array(
    // выравнивание
    'alignment' => array(
        'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
    ),
    // заполнение цветом
    'fill' => array(
        'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
        'color'=>array(
            'rgb' => 'CFCFCF'
        )
    ),
    // шрифт
    'font'=>array(
        'bold' => true,
        //'italic' => true,
        'name' => 'Times New Roman',
        'size' => 10
    ),
);
$aSheet->getStyle('A6:E6')->applyFromArray($style_hprice);

// Cтили для данных в таблице прайс-листа
$style_price = array(
    'alignment' => array(
        'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
    )
);
$aSheet->getStyle('A7:E'.($i+5))->applyFromArray($style_price);


ob_end_clean();
date_default_timezone_set('europe/kiev');
//header('Content-Type:xlsx:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition:attachment;filename="simple.xlsx"');
//$objWriter = new PHPExcel_Writer_Excel2007($pExcel);

header('Content-Type:application/vnd.ms-excel');
header('Content-Disposition:attachment;filename="simple.xls"');
$objWriter = new PHPExcel_Writer_Excel5($pExcel);
$objWriter->save('php://output');