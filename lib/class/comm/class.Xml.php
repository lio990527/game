<?php 
///////////////////
//携程XML接口访问类
//lio
//2014-05-25
///////////////////
class Xml{

	/**
	 * 将数组转换为XML
	 * @param $arrAttr
	 * @return string 遍历过后的结果
	 */
	public static function arrayToXml($arrAttr, $strHeader = '', $strKong = ''){
		$strXmlRes = '';
		if(is_array($arrAttr) && count($arrAttr)){
			$strKong .= '  ';
			foreach ($arrAttr as $key => $value){
				if(is_array($value) && count($value)){

					if($value['AttrBute']){
						$strAttr = $key;
						foreach($value['AttrBute'] as $k=>$v){
							$strAttr.= ' '.$k.'="'.$v.'"';
						}
						unset($value['AttrBute']);
						if(is_array($value) && count($value)){
							$strXmlRes .= $strKong.'<'.$strHeader.$strAttr.'>'."\n".self::arrayToXml($value, $strHeader, $strKong).$strKong.'</'.$strHeader.$key.'>'."\n";
						}else{
							$strXmlRes .= $strKong.'<'.$strHeader.$strAttr.'/>'."\n";
						}
						continue;
					}else if(is_int($key)){
						$strXmlRes .= self::arrayToXml($value, $strHeader, substr($strKong, 0, strlen($strKong)-2));
					}else{
						$strXmlRes .= $strKong.'<'.$strHeader.$key.'>'."\n".self::arrayToXml($value, $strHeader, $strKong).$strKong.'</'.$strHeader.$key.'>'."\n";
					}
				}else{
					$strXmlRes .= $strKong.'<'.$strHeader.$key.'>'.$value.'</'.$strHeader.$key.'>'."\n";
				}
			}
		}
		return $strXmlRes;
	}
	
	public static function array2xml($array) {
		$xml = '';
		foreach($array as $key=>$val) {
			is_numeric($key)&&$key='item';
			$xml.="<$key>";
			$xml.=is_array($val)?self::array2xml($val): htmlspecialchars($val);
			$xml.="</$key>";
		}
		return $xml;
	}

	/**
	 * XML转换成数组
	 * @param XML $xml
	 * @param unknown_type $attributesKey
	 * @param unknown_type $childrenKey
	 * @param unknown_type $valueKey
	 * mike
	 */
	public static function simpleXMLToArray(SimpleXMLElement $xml,$attributesKey=null,$childrenKey=null,$valueKey=null){

		if($childrenKey && !is_string($childrenKey)){
			$childrenKey = '@children';
		}
		if($attributesKey && !is_string($attributesKey)){
			$attributesKey = '@attributes';
		}
		if($valueKey && !is_string($valueKey)){
			$valueKey = '@values';
		}

		$return = array();
		$name = $xml->getName();
		$_value = trim((string)$xml);
		if(!strlen($_value)){
			$_value = null;
		}
		if($_value!==null){
				
			if($valueKey){
				$return[$valueKey] = $_value;
			}else{
				$return = $_value;
			}
		}

		$children = array();
		$first = true;
		foreach($xml->children() as $elementName => $child){

			$value = self::simpleXMLToArray($child,$attributesKey, $childrenKey,$valueKey);
			if(isset($children[$elementName])){
				if(is_array($children[$elementName])){
					if($first){
						$temp = $children[$elementName];
						unset($children[$elementName]);
						$children[$elementName][] = $temp;
						$first=false;
					}
					$children[$elementName][] = $value;
				}else{
					$children[$elementName] = array($children[$elementName],$value);
				}
			}else{

				if(count($value)){
					$children[$elementName] = $value;
				}else{
					$children[$elementName] = "";
				}
			}
		}
		if($children){
			if($childrenKey){
				$return[$childrenKey] = $children;
			}else{
				$return = array_merge($return,$children);
			}
		}

		$attributes = array();
		foreach($xml->attributes() as $name=>$value){
			$attributes[$name] = trim($value);
		}
		if($attributes){
			if($attributesKey){
				$return[$attributesKey] = $attributes;
			}else{
				$return = array_merge($return, $attributes);
			}
		}
		unset($xml);
		unset($children);
		unset($value);
		unset($attributes);
		return $return;
	}
}