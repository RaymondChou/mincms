<?php  
/**
 * @author Sun Kang <68103403@qq.com>
 * @link http://www.mincms.com/
 * @copyright 2013-2013 MinCMS Software
 * @license http://www.mincms.com/license/
 */
class ArrHelper{
	/**
	 * ����ά���� ת��һά����
	 *
	 * @param unknown_type $array
	 * @return unknown
	 */
	static function one($array)
	{
		$arrayValues = array();
		$i = 0;
		foreach ($array as $key=>$value)
		{
			if (is_scalar($value) or is_resource($value))
			{
				$arrayValues[$key] =$span.$value;
			}
			elseif (is_array($value) || is_object($value))
			{ 
				$value = (array)$value;
				$arrayValues = array_merge($arrayValues, self::one($value));
			}
		} 
		return $arrayValues;
	}
	/**
	 * ����ά���� ת��һά����,����ԭ����key
	 *
	 * @param unknown_type $array
	 * @return unknown
	 */
	static function one_key($array)
	{
		$arrayValues = array();
		$i = 0;
		foreach ($array as $key=>$value)
		{
			if (is_scalar($value) or is_resource($value))
			{
				$arrayValues[$key] = $value;
			}
			elseif (is_array($value) || is_object($value))
			{ 
				$value = (array)$value;
				$arrayValues = self::array_merge($arrayValues, self::one($value));
			}
		}

		return $arrayValues;
	}
	static function array_merge($a,$b){
		foreach ($a as $key => $value) {
			 $out[$key] = $value;
		} 
		foreach ($b as $key => $value) {
			 $out[$key] = $value;
		}
		return $out;
	}
 

	 /**
	 * 
	 * �Զ�ά�������group by����
	 * @param ���� $arr
	 * @param group  by ���ֶ� $group
	 */
	static function group_by($arr,$groupby="sid"){
		static $array = array();
		static $key = array(); 
		foreach ($arr as $k=>$v){
			$g = $v[$groupby];
			if(!in_array($g,$key)){
				$key[$k] = $g; 
			} 
			$array[$g][] = $v;
			
		}
		return $array;
	}
	/**
	 * ��ά����ת�ɶ�ά
	 * @param unknown_type $arr
	 */
	static function array_values_one($arr,$exp=null) { 
		foreach ( $arr as $v ) {
		 	if($v){
				foreach ( $v as $val ){
					if($v[$exp]){
						unset($v[$exp]);
					} else
						$new [] = $val;
				}
			}
		}
		return $new;
	}
	/**
	 * ��������
	 * Arr_helper::array_orderby($row,$order,SORT_DESC);
	 */
	static function array_orderby() {
		$args = func_get_args ();
		$data = array_shift ( $args );
		foreach ( $args as $n => $field ) {
			if (is_string ( $field )) {
				$tmp = array ();
				if(!$data) return;
				foreach ( $data as $key => $row )
					$tmp [$key] = $row [$field];
				$args [$n] = $tmp;
			}
		}
		$args [] = &$data;
		if($args){
			call_user_func_array ( 'array_multisort', $args );
			return array_pop ( $args );
		}
		return;
	}
	/*
	* array_combine����һ�����⣬����array����������ͬ��
	* ���ڵ����������Ϊ�˽����������
	* array_combine �� Creates an array by using one array for keys and another for its values
	*/
	function array_combine($a,$b){
		$i=0;
		foreach($a as $k){
		  $rt[$k] = $b[$i];
		  $i++;
		}
		return $rt;
	}
	
	/**
	 * 
	 * �ϲ�����
	 * accumulated  Ϊtrueʱ�ϲ�����ֵ
	 * @param unknown_type $arr
	 * $showky �� $pk ��Ϊ����keyֵ
	 */
	static function combine($arr,$groupby='sid',$combine_fileds=false){ 
		$arr = self::array_values_one($arr,$groupby); 
		static $new = array();
		foreach($arr as $key => $val){  
		 	foreach($val as $k=>$v){
			 	$n[$v[$groupby]] = $v;
			    foreach($combine_fileds as $c){ 
			    	$new[$v[$groupby]][$c] += $v[$c];
			    } 
		 	}
		} 
		foreach($n as $k=>$v){
			$out[] = array_merge($v,$new[$k]);
		}
		return $out; 
	}



	
	
}
 