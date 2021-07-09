<?php 

$url1 = 'http://www.tiebaobei.com/publish/';
$url2 = 'http://www.tiebaobei.com/ajax/ue/getSecondCategoryList?categoryId=%d';
$url3 = 'http://www.tiebaobei.com/ajax/ue/getNewBrandList?secondCategoryId=%d';
$url4 = 'http://www.tiebaobei.com/ajax/ue/getSeriesList?secondCategoryId=%d&brandId=%d';
$url5 = 'http://www.tiebaobei.com/ajax/ue/getNewModelList?secondCategoryId=%d&brandId=%d&serialId=%d';

$res1 = file_get_contents($url1);
$pregReg = '/<ul\s*id=\"categorySelect\">(.*)<\/div>/Us';
$arrTemp = array();
preg_match($pregReg, $res1, $arrTemp);
// print_r();exit;
$list = explode("\n", $arrTemp[1]);
// var_dump($list);

require_once '../lib/class/comm/class.file.php';

$fileName = 'laopo.txt';
$fileBrand = 'brand.txt';

$brandTitle = 'id,name,enName,initial,logo,country,sortOrder,brandDesc,supporter,email,phone,zip,address,fax,siteUrl,officialSite,homeOrder,isgood,showBrandEnName';
File::writeFile('', $fileBrand, $brandTitle."\n");

foreach ($list as $li){
	$li = trim($li);
	if($li == '' || $li == '</ul>'){
		continue;
	}
	
	$pregReg = '/<li\s*data-id=\"(\d+)\">([^<]*)<\/li>/Us';
	$arrLi = array();
	preg_match($pregReg, $li, $arrLi);
	if(count($arrLi) < 3){
		continue;
	}
	
	$strName = $arrLi[2];
	var_dump($strName);
	$secondUrl = sprintf($url2, $arrLi[1]);
	$secondRes = file_get_contents($secondUrl);
	$secondRes = json_decode($secondRes, true);
	if(!empty($secondRes['result'])){
		foreach ($secondRes['result'] as $category){
// 			var_dump($category);
			$strCategory = $strName.','.$category['name'];
			$brandUrl = sprintf($url3, $category['id']);
			$brandRes = file_get_contents($brandUrl);
			$brandRes = json_decode($brandRes, true);
			if(!empty($brandRes['result'])){
				foreach ($brandRes['result'] as $brand){
					File::writeFile('', $fileBrand, implode('|', $brand)."\n");
// 					var_dump($brand);
					$strBrand = $strCategory.','.$brand['name'];
					$seriesURl = sprintf($url4, $category['id'], $brand['id']);
					$seriesRes = file_get_contents($seriesURl);
					$seriesRes = json_decode($seriesRes, true);
					if(!empty($seriesRes['result'])){
						foreach ($seriesRes['result'] as $series){
// 							var_dump($series);
							$strSeries = $strBrand.','.$series['name'];
							$modeUrl = sprintf($url5, $category['id'], $brand['id'], $series['id']);
							
							$modeRes = file_get_contents($modeUrl);
							$modeRes = json_decode($modeRes, true);
// 							var_dump($series,$modeUrl,$modeRes);exit;
							if(!empty($modeRes['result'])){
								foreach ($modeRes['result'] as $mode){
// 									var_dump($mode);
									$strMode = $strSeries.','.$mode['modelName'];
// 									var_dump($strRow);exit;
									File::writeFile('', $fileName, $strMode."\n");
									echo $strMode."\n";
								}
							}else{
								File::writeFile('', $fileName, $strSeries."\n");
							}
						}
					}else{
						File::writeFile('', $fileName, $strBrand."\n");
					}
				}
			}else{
				File::writeFile('', $fileName, $strCategory."\n");
			}
		}
	}else{
		File::writeFile('', $fileName, $strName."\n");
	}
}
var_dump('exit and end.');exit;
?>