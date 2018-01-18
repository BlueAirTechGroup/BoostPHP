<?php
class BoostPHP_Check{
	// 函数名：CheckMoney($C_Money)    
	// 作 用：检查数据是否是99999.99格式    
	// 参 数：$C_Money（待检测的数字）    
	// 返回值：布尔值    
	// 备 注：无    
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function CheckMoney($C_Money)    
	{
		return preg_match("/^[0-9][.][0-9]$/", $C_Money) ? true : false;    
	}
	 
	 
	 
	//-----------------------------------------------------------------------------------    
	 
	 
	 
	// 函数名：CheckEmailAddr($C_mailaddr)    
	// 作 用：判断是否为有效邮件地址    
	// 参 数：$C_mailaddr（待检测的邮件地址）    
	// 返回值：布尔值    
	// 备 注：无    
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function CheckEmailAddr($C_mailaddr)    
	{  
		return preg_match("/^[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*@[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$/", $C_mailaddr) ? true : false;
	}
	 
	 
	 
	//-----------------------------------------------------------------------------------    
	 
	 
	 
	// 函数名：CheckWebAddr($C_weburl)    
	// 作 用：判断是否为有效网址    
	// 参 数：$C_weburl（待检测的网址）    
	// 返回值：布尔值    
	// 备 注：无    
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function CheckWebAddr($C_weburl)    
	{    
		return((preg_match("/^http://[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$/", $C_weburl) || preg_match("/^https://[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$/" ,$C_weburl)) ? true : false);
	}    
	 
	 
	 
	//-----------------------------------------------------------------------------------   
	 
	 
	 
	//-----------------------------------------------------------------------------------    
	 
	 
	 
	// 函数名：CheckLengthBetween($C_char, $I_len1, $I_len2=100)    
	// 作 用：判断是否为指定长度内字符串    
	// 参 数：$C_char（待检测的字符串）    
	// $I_len1 （目标字符串长度的下限）    
	// $I_len2 （目标字符串长度的上限）    
	// 返回值：布尔值    
	// 备 注：无    
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function CheckLengthBetween($C_cahr, $I_len1, $I_len2=100)    
	{    
		$C_cahr = trim($C_cahr);    
		if (strlen($C_cahr) < $I_len1) return false;    
		if (strlen($C_cahr) > $I_len2) return false;    
		return true;    
	}
	 
	 
	 
	//-----------------------------------------------------------------------------------    
	 
	 
	 
	// 函数名：CheckUser($C_user)    
	// 作 用：判断是否为合法用户名    
	// 参 数：$C_user（待检测的用户名）    
	// 返回值：布尔值    
	// 备 注：无    
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function CheckUser($C_user,$min_CharNum = 0, $max_CharNum = -1)    
	{
		if($min_CharNum > 0 || $max_CharNum != -1){
			if($max_CharNum == -1){$max_CharNum = strlen($C_user);}
			if (!BoostPHP_Check::CheckLengthBetween($C_user, $min_CharNum, $max_CharNum)) return false; //宽度检验
		}
		return preg_match("/^[_a-zA-Z0-9]*$/", $C_user) ? true : false; //特殊字符检验
	}
	
	 
	 
	
	 
	 
	// 函数名：CheckPassword($C_passwd)    
	// 作 用：判断是否为合法用户密码    
	// 参 数：$C_passwd（待检测的密码）    
	// 返回值：布尔值    
	// 备 注：无
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function CheckPassword($C_passwd,$min_CharNum = 0, $max_CharNum = -1)    
	{    
		if($min_CharNum > 0 || $max_CharNum != -1){
			if($max_CharNum == -1){$max_CharNum = strlen($C_passwd);}
			if (!BoostPHP_Check::CheckLengthBetween($C_passwd, $min_CharNum, $max_CharNum)) return false; //宽度检验
		}
		return preg_match("/^[_a-zA-Z0-9]*$/", $C_passwd) ? true : false; //特殊字符检验   
	}    
	 
	 
	 
	//-----------------------------------------------------------------------------------    
	 
	 
	 
	// 函数名：CheckTelephone($C_telephone)    
	// 作 用：判断是否为合法电话号码    
	// 参 数：$C_telephone（待检测的电话号码）    
	// 返回值：布尔值    
	// 备 注：无    
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function CheckTelephone($C_telephone)    
	{
		if(strlen($C_telephone) != 11) return false;
		return preg_match("/^[+]?[0-9]+([xX-][0-9]+)*$/", $C_telephone) ? true : false;    
	}
	 
	 
	 
	//-----------------------------------------------------------------------------------    
	 
	 
	 
	// 函数名：CheckValueBetween($N_var, $N_val1, $N_val2)    
	// 作 用：判断是否是某一范围内的合法值    
	// 参 数：$N_var 待检测的值    
	// $N_var1 待检测值的上限    
	// $N_var2 待检测值的下限    
	// 返回值：布尔值    
	// 备 注：无    
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function CheckValueBetween($N_var, $N_val1, $N_val2)
	{
		if($N_var < $N_var1 || $N_var > $N_var2)
		{
			return false;
		}else{
			return true;
		}
	}
	
	 
	 
	 
	// 函数名：CheckPost($C_post)    
	// 作 用：判断是否为合法邮编（固定长度）    
	// 参 数：$C_post（待check的邮政编码）    
	// 返回值：布尔值    
	// 备 注：无    
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function CheckPostNum($C_post)    
	{    
		$C_post=trim($C_post);
		if(strlen($C_post) != 6){return false;}
		return preg_match("/^[+]?[_0-9]*$/",$C_post) ? true : false;   
	}    
	//-----------------------------------------------------------------------------------    
	 
	 
	 
	//-----------------------------------------------------------------------------------    
	 
	 
	 
	// 函数名：ExchangeMoney($N_money)    
	// 作 用：资金转换函数    
	// 参 数：$N_money（待转换的金额数字）    
	// 返回值：字符串    
	// 备 注：本函数示例：$char=ExchangeMoney(5645132.3155) ==> $char='￥5,645,132.31'    
	//-----------------------------------------------------------------------------------    
	 
	 
	public static function ExchangeMoney($N_money)    
	{    
	$A_tmp=explode(".",$N_money ); //将数字按小数点分成两部分，并存入数组$A_tmp    
	$I_len=strlen($A_tmp[0]); //测出小数点前面位数的宽度    
	 
	 
	if($I_len%3==0)    
	{    
	$I_step=$I_len/3; //如前面位数的宽度mod 3 = 0 ,可按，分成$I_step 部分    
	}else    
	{    
	$step=($len-$len%3)/3+1; //如前面位数的宽度mod 3 != 0 ,可按，分成$I_step 部分+1    
	}    
	 
	 
	$C_cur="";    
	//对小数点以前的金额数字进行转换    
	while($I_len<>0)    
	{    
	$I_step--;    
	 
	 
	if($I_step==0)    
	{    
	$C_cur .= substr($A_tmp[0],0,$I_len-($I_step)*3);    
	}else    
	{    
	$C_cur .= substr($A_tmp[0],0,$I_len-($I_step)*3).",";    
	}    
	 
	 
	$A_tmp[0]=substr($A_tmp[0],$I_len-($I_step)*3);    
	$I_len=strlen($A_tmp[0]);    
	}    
	 
	 
	//对小数点后面的金额的进行转换    
	if($A_tmp[1]=="")    
	{    
	$C_cur .= ".00";    
	}else    
	{    
	$I_len=strlen($A_tmp[1]);    
	if($I_len<2)    
	{    
	$C_cur .= ".".$A_tmp[1]."0";    
	}else    
	{    
	$C_cur .= ".".substr($A_tmp[1],0,2);    
	}    
	}    
	 
	 
	//加上人民币符号并传出    
	$C_cur="￥".$C_cur;    
	return $C_cur;    
	}    	
}
?>