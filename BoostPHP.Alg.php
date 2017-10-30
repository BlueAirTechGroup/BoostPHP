<?php
/**
* Algorithm Class
* Author: Windy(www.xsyds.cn)
*/

$BoostPHP_CompareField ='field';
/**
 * Do not use this function! This is only used inside the BoostPHP_Algorithm Class
 * @param double $a
 * @param double $b
 * @return number
 */
function quickSortArrays_CompareFunc(&$a,&$b){
    global $BoostPHP_CompareField;
    if($a[$BoostPHP_CompareField] == $b[$BoostPHP_CompareField]){
        return 0;
    }
    return ($a[$BoostPHP_CompareField] < $b[$BoostPHP_CompareField]) ? -1 : 1;
}
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
	public function quickSortArrays_ByField($array,$field){
	    global $BoostPHP_CompareField;
	    $BoostPHP_CompareField = $field;
	    $newArray = $array;
	    usort($newArray, "quickSortArrays_CompareFunc");
	    return $newArray;
	}
}
?>