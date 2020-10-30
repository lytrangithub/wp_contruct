<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
//Load thư viện PhpWord
require_once get_template_directory()."/inc/third-party/PhpWord/PHPWord.php";
use \PhpOffice\PhpWord\Settings;

// Creating the new document...
$phpWord = new \PhpOffice\PhpWord\PhpWord();
\PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
// Adding an empty Section to the document...
$section = $phpWord->addSection();
$header = $section->createHeader();
$footer = $section->createFooter();
$header -> addImage(DEF_IMAGES.'Header_word.jpg', array('width' => 460, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT));
$footer -> addImage(DEF_IMAGES.'Footer_word.jpg', array('width' => 460, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT));

//Header title
$section->addText('履歴書', array('size' => 24, 'color' => '333333', 'bold' => true), array('alignment' => 'center', 'space' => array('before' => 200, 'after' => 200)));
$section->addText('CURRICULUM VITAE', array('size' => 24, 'color' => '333333', 'bold' => true), array('alignment' => 'center', 'space' => array('before' => 0, 'after' => 200)));

$fancyTableStyle = array('borderSize' => 4, 'borderColor' => '666666', 'cellMargin' => 60);
$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFF00');
//Common
$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
$cellVCentered = array('valign' => 'center');
$cellFirst = array('bgColor' => 'D7FDA4', 'padding' => 30);
$cellFirstW = 4500;
$cellLast = array('gridSpan' => 3);
$cellLastW = 9000;
$cellTxtStyle = array('size' => 12);
//Table row
$spanTableStyleName = 'Table row';
$phpWord->addTableStyle($spanTableStyleName, $fancyTableStyle);
$table = $section->addTable($spanTableStyleName);
//Row 1
$table->addRow();
$cell1 = $table->addCell($cellFirstW, $cellFirst);
$cell1->addText('タイトル　Title', $cellTxtStyle);
$cell2 = $table->addCell($cellLastW , $cellLast);
$cell2->addText('text', $cellTxtStyle);
//Row 2
$table->addRow();
$cell1 = $table->addCell($cellFirstW, $cellFirst);
$cell1->addText('分野 Field', $cellTxtStyle);
$cell2 = $table->addCell($cellLastW , $cellLast);
$cell2->addText('text', $cellTxtStyle);
//Row 3
$table->addRow();
$cell1 = $table->addCell($cellFirstW, $cellFirst);
$cell1->addText('名前　Full Name', $cellTxtStyle);
$cell2 = $table->addCell($cellLastW , $cellLast);
$cell2->addText('text', $cellTxtStyle);
//Row 4
$table->addRow();
$cell1 = $table->addCell($cellFirstW, $cellFirst);
$cell1->addText('生年月日 Day of Birth', $cellTxtStyle);
$cell2 = $table->addCell($cellLastW , $cellLast);
$cell2->addText('text', $cellTxtStyle);
//Row 5
$table->addRow();
$cell1 = $table->addCell($cellFirstW, $cellFirst);
$cell1->addText('性別　Gender', $cellTxtStyle);
$cell2 = $table->addCell(2000);
$cell2->addText('text', $cellTxtStyle);
$cell3 = $table->addCell(3500, $cellFirst);
$cell3->addText('配偶者Marital Status', $cellTxtStyle);
$cell3 = $table->addCell(2000);
$cell3->addText('text afdsfsd', $cellTxtStyle);
//Row 6
$table->addRow();
$cell1 = $table->addCell($cellFirstW, $cellFirst);
$cell1->addText('住所　Address', $cellTxtStyle);
$cell2 = $table->addCell($cellLastW , $cellLast);
$cell2->addText('text', $cellTxtStyle);
//Row 7
$table->addRow();
$cell1 = $table->addCell($cellFirstW, $cellFirst);
$cell1->addText('メール Mail', $cellTxtStyle);
$cell2 = $table->addCell($cellLastW , $cellLast);
$cell2->addText('text', $cellTxtStyle);
//Row 8
$table->addRow();
$cell1 = $table->addCell($cellFirstW, $cellFirst);
$cell1->addText('フェイスブック Facebook', $cellTxtStyle);
$cell2 = $table->addCell($cellLastW , $cellLast);
$cell2->addText('text', $cellTxtStyle);
//Row 9
$table->addRow();
$cell1 = $table->addCell($cellFirstW, $cellFirst);
$cell1->addText('携帯電話番号 Phone Number', $cellTxtStyle);
$cell2 = $table->addCell($cellLastW , $cellLast);
$cell2->addText('text', $cellTxtStyle);
//=========================================
$section->addText('■ 学歴 Educational Background', array('size' => 14, 'color' => '333333', 'bold' => true), array('alignment' => 'left', 'space' => array('before' => 200, 'after' => 200)));
$table1 = $section->addTable($spanTableStyleName);
//Row 1
$table1->addRow();
$cell1 = $table1->addCell($cellFirstW, $cellFirst);
$cell1->addText('学歴 Educational Background', $cellTxtStyle);
$cell2 = $table1->addCell($cellLastW);
$cell2->addText('text', $cellTxtStyle);
//Row 2
$table1->addRow();
$cell1 = $table1->addCell($cellFirstW, $cellFirst);
$cell1->addText('語学力 Language', $cellTxtStyle);
$cell2 = $table1->addCell($cellLastW);
$cell2->addText('text', $cellTxtStyle);
//=========================================
$section->addText('■ 職歴 　Work Experience', array('size' => 14, 'color' => '333333', 'bold' => true), array('alignment' => 'left', 'space' => array('before' => 200, 'after' => 200)));
$table2 = $section->addTable($spanTableStyleName);
//Row 1
$table2->addRow();
$cell1 = $table2->addCell(10000, $cellFirst);
$cell1->addText('職歴　Work Experience', $cellTxtStyle, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
//Row 2
$table2->addRow();
$cell1 = $table2->addCell(10000);
$cell1->addText('text', $cellTxtStyle);
//=========================================
$section->addText('■ 翻訳経験 Translation Experience & 翻訳分野Translation fields', array('size' => 14, 'color' => '333333', 'bold' => true), array('alignment' => 'left', 'space' => array('before' => 200, 'after' => 200)));
$table3 = $section->addTable($spanTableStyleName);
//Row 1
$table3->addRow();
$cell1 = $table3->addCell(10000);
$cell1->addText('text');
//Row 2
//Tạo thư mục uplaod nếu không tồn tại
if (! is_dir(UPLOAD_TERM_DIR)) {
    $old_umask = umask(0);
    mkdir( UPLOAD_TERM_DIR, 0777, 1);
    umask($old_umask);
}
//Set thư mục upload tạm
Settings::setTempDir(UPLOAD_TERM_DIR);
$file_name = 'test';
//$file_path = tempnam(UPLOAD_TERM_DIR, $file_name.'_'.date ( 'Ymd' ).'_').'.docx';
$file_path = UPLOAD_TERM_DIR.$file_name.'_'.date ( 'Ymd_his' ).'.docx';
// Saving the document as OOXML file...
//$test = \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

$objWriter->save($file_path);
