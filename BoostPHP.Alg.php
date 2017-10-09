<?php
/**
* Algorithm Class
* Author: Windy(www.xsyds.cn)
*/
class BoostPHP_AlgorithmClass{
	/**
	* Sort Array by Native Quick Sort Method(Partition)
	* About 20 times faster then quicksort implemented by the PHP code.
	* @param array $array array to be sorted
	* @return array the array after sorting
	*/
	public function quickSort_Native($array){
	    $NewArr = $array;
		sort($NewArr);
		return $NewArr;
	}
	
	/**
	* Sort Array by QuickSort Method(Partition)
	* Array should be in the format of array(array(..., field => int), array(..., field => int));
	* Example: quickSortArrays_ByField(array("Content"=>"Philosophy No.1", "ListedBy"=>1), array("Content")=>"Math No.2", "ListedBy"=>2)),"ListedBy");
	* Original Author Britain / Yi_Zhi_Yu (https://segmentfault.com/a/1190000000758502)
	* Modified by Windy(www.xsyds.cn)
	* @param array $array array to be sorted
	* @return array the array after sorting
	*/
	public function quickSortArrays_ByField($array, $field){
		//if(!isset($array[1]))
		if(count($array) <= 1)
			return $array;
		$base = $array[0][$field];
		$leftArray = array();
		$rightArray = array();
		$midArray = array();
		// 修改部分------------------------
		for($i=1; $i<count($array); $i++){
			if($array[$i][$field] > $base){
				$rightArray[] = $array[$i];
		    }else if($array[$i][$field]==$base){
			    $midArray[] = $array[$i];
			}else{ // <= base
				$leftArray[] = $array[$i];
			}
		}
		//------------------------------------
		$leftArray = $this->quickSortArrays_ByField($leftArray,$field);
		//$leftArray[] = $base;
		
		$rightArray = $this->quickSortArrays_ByField($rightArray,$field);
		return array_merge($leftArray, array($base), $midArray, $rightArray);
	}
}
?>