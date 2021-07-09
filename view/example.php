<?php
// �����ĵ�

require_once 'class/comm/Excel/reader.php';

// Excel�ļ�($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();


// ����������� UTF-8/GB2312/CP936�ȵ�
$data->setOutputEncoding('UTF-8'); 

/***
* �����������֧�� iconv �������Ĵ���ʹ�� mb_convert_encoding ����
* $data->setUTFEncoder('mb');
*
**/

/***
* Ĭ��������к��еļ�����1��ʼ
* ���Ҫ�޸���ʼ��ֵ����ӣ�
* $data->setRowColOffset(0);
*
**/


/***
*  ���ù���ģʽ
* $data->setDefaultFormat('%.2f');
* setDefaultFormat - ������ģʽ
*
* $data->setColumnFormat(4, '%.3f');
* setColumnFormat - �еĸ�ʽ���ã��������������ֶΣ�
*
**/

// $data->read('jxlrwtest.xls');
$data->read('source/upload/abc.xls');

/*


 $data->sheets[0]['numRows'] - ����
 $data->sheets[0]['numCols'] - ����
 $data->sheets[0]['cells'][$i][$j] - ��$i ��$j�������

 $data->sheets[0]['cellsInfo'][$i][$j] - �ļ�����չ��Ϣ
    
    $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
    	��typeΪunknownʱʹ��rawֵ����ΪԪ���а���'0.00'�ĸ�ʽ��
    $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = δ����ʽ����ֵ
    $data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
    $data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
*/

error_reporting(E_ALL ^ E_NOTICE);

for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
// 	var_dump($data->sheets[0]['cells'][$i]);exit;
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo $data->sheets[0]['cells'][$i][$j]."\t";
	}
	echo "\n";

}


//print_r($data);
//print_r($data->formatRecords);
?>
