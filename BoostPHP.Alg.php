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
	function quickSort_Native($array){
		return sort($array);
	}
	
	/**
	* Sort Array by QuickSort Method(Partition)
	* Original Author Britain / Yi_Zhi_Yu (https://segmentfault.com/a/1190000000758502)
	* Modified by Windy(www.xsyds.cn)
	* @param array $array array to be sorted
	* @return array the array after sorting
	*/
	function quickSort_Slow($array){
		//if(!isset($array[1]))
		if(count($array) <= 1)
			return $array;
		$base = $array[0];
		$leftArray = array();
		$rightArray = array();
		
		// 修改部分------------------------
		for($i=1; $i<count($array); $i++){
			if($array[$i] > $base)
				$rightArray[] = $array[$i];
			else // <= base
				$leftArray[] = $array[$i];
		}
		//------------------------------------
		
		$leftArray = quickSort($leftArray);
		//$leftArray[] = $base;
		
		$rightArray = quickSort($rightArray);
		return array_merge($leftArray, array($base), $rightArray);
	}
}
?>