<?php
/* Powered by xsyds.cn(C)2017
* Please refer to the MIT copyright statement when modifying
* To utilize this framwork, require_once this file
*/
/* You can:	
require_once 'BoostPHP.Alg.php';
require_once 'BoostPHP.AES.php';
require_once 'BoostPHP.RSA.php';
require_once 'BoostPHP.International.php';
*/
require_once 'class.phpmailer.php';
class BoostPHP_ResultClass{
	/**
	 * Returns the IP of the visitor
	 * @param bool If you want to automatic detect your visitors' IP behind a CDN, might cause security issue.
	 * @access public
	 * @return string
	 */
	public static function getIP($detectCDN = true){
		if($detectCDN){
			return empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["REMOTE_ADDR"] : $_SERVER["HTTP_X_FORWARDED_FOR"];
		}else{
			return $_SERVER["REMOTE_ADDR"];
		}
	}
	
	/**
	 * Jump to a page using header writing, JavaScript and HTML tags, and ALSO Exit the PHP program right away.
	 * @param string $URL the URL you want to jump to
	 * @access public
	 * @return void
	 */
	public static function jumpToPage($URL){
		header("Location: " . $URL);
		echo '<script>document.location="' . $URL . '";window.location="' . $URL . '";</script>';
		echo '<noscript><meta http-equiv="refresh" content="0;URL=\'' . $URL . '\'" /></nocript>';
		exit(0);
	}
	/**
	 * Cache a page result, start to cache
	 * @param int $cacheAvailableTime Time until new cache is generated
	 * @param bool $useAutoGenerate set this to true if you want the system to auto generate filename for you.
	 * @param string $cacheFileName cached Filename(if useAutoGenerate is on, it should be the basedir, which looks like dir/)
	 * @access public
	 * @return bool needed to cache
	 */
	public static function cacheStart($cacheAvailableTime, $useAutoGenerate ,$cacheFileName = ''){
		ob_start();
		if($useAutoGenerate){
			$cacheFileName .= sha1($_SERVER['REQUEST_URI']) . sha1(file_get_contents("php://input")) . '.html';
		}
		if(file_exists($cacheFileName) && (time()-filemtime($cacheFileName)) < $cacheAvailableTime){
			include $cacheFileName;
			return false;
		}
		return true;
	}
	/**
	 * Stop caching a page result and put the caching content into a file
	 * @param bool $useAutoGenerate set this to true if you want the system to auto generate filename for you.
	 * @param string $cacheFileName cached Filename(if useAutoGenerate is on, it should be the basedir, which looks like dir/)
	 * @param bool $isStoring is storing the content of the cache content
	 * @access public
	 * @return void
	 */
	public static function cacheEnd($useAutoGenerate, $cacheFileName = '', $isStoring=true){
		if($useAutoGenerate){
			$cacheFileName .= sha1($_SERVER['REQUEST_URI']) . sha1(file_get_contents("php://input")) . '.html';
		}
		if($isStoring){
			$fp = fopen($cacheFileName,'w');
			fwrite($fp,ob_get_contents());
			fclose($fp);
		}
		ob_end_flush();
	}
}

class BoostPHP_StringClass{
	public static function wordLimit($str, $length = 0, $append = true)
	{
		$str = trim($str);
		$strlength = strlen($str);
	 
		if ($length == 0 || $length >= $strlength)
		{
			return $str;  //截取长度等于0或大于等于本字符串的长度，返回字符串本身
		}
		elseif ($length < 0)  //如果截取长度为负数
		{
			$length = $strlength + $length;//那么截取长度就等于字符串长度减去截取长度
			if ($length < 0)
			{
				$length = $strlength;//如果截取长度的绝对值大于字符串本身长度，则截取长度取字符串本身的长度
			}
		}
	 
			//$newstr = trim_right(substr($str, 0, $length));
		$newstr = substr($str, 0, $length);
	 
		if ($append && ($str != $newstr))
		{
			$newstr .= '...';
		}
	 
		return $newstr;
	}
	
}
class BoostPHP_NetworkClass{
	/**
	 * Send a mail using SMTP
	 * returns false on failure
	 * @param int Port for SMTP
	 * @param string Host for the SMTP
	 * @param string Username for the SMTP
	 * @param string Password for the SMTP
	 * @param string Who are u sending to, can be multi users, split them by adding semi-colun(;)
	 * @param string Your email subject
	 * @param string Your email body(Supports HTML5)
	 * @param string Your Sender Address(Usually same with the Username)
	 * @param string Your Sender Name(E.g. BlueAirTechGroup)
	 * @param string ssl / tls / empty for nonsecure
	 * @access public
	 * @return Mysql Connection
	 */
	public static function sendEmail($SMTPPort = 25,$SMTPHost,$SMTPUsername,$SMTPPassword,$To,$Subject,$Body,$Sender,$SenderName, $SecureConnection = 'ssl'){
		$MySD=new PHPMailer;
		$MySD->IsSMTP();
		$MySD->isHTML(true);
		$MySD->CharSet = 'utf-8';
		$MySD->SMTPAuth = true;
		$MySD->Port = $SMTPPort;
		$MySD->Host = $SMTPHost;
		$MySD->Username = $SMTPUsername;
		$MySD->Password = $SMTPPassword;
		$MySD->From = $Sender;
		$MySD->FromName = $SenderName;
		if(!empty($SecureConnection)){
			if($SecureConnection == 'tls'){
				$MySD->SMTPSecure = 'tls';
			}else{
				$MySD->SMTPSecure = 'ssl';
			}
		}
		
		if(strpos($To,";")!==false){
			//如果分为好几个收件人
			$MyRSVA=explode(";",$To);
			foreach($MyRSVA as $Addr){
				if(!empty($Addr)){
					$MySD->addAddress($Addr);
				}
			}
		}else{
			if($MySD->addAddress($To)==false){
				return false;
			}
		}
		$MySD->Subject = $Subject;
		$MySD->Body=$Body;
		$MySD->send();
		return true;
	}
	/**
	 * Send a Post Request
	 * returns false on failure
	 * @param string URL Posting To
	 * @param array Data to POST(In array Key=>Value)
	 * @param string Reference(Which website were you previously?)
	 * @param array Cookies to POST with
	 * @access public
	 * @return array {'code'=>HTTPStat, 'content'=>Content}
	 */
	public static function postToAddr($url,$data,$ref,$cookie = null){ // 模拟提交数据函数
		$curl = curl_init(); // 启动一个CURL会话
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		if(!empty($cookie)){
			curl_setopt($curl, CURLOPT_COOKIE, $cookie);
		}
		curl_setopt($curl, CURLOPT_REFERER, $ref);// 设置Referer
		curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		$tmpInfo = curl_exec($curl); // 执行操作
		if (curl_errno($curl)) {
		   return false;//捕抓异常
		}
		$tmpRst['code'] = curl_getinfo($curl,CURLINFO_HTTP_CODE); //我知道HTTPSTAT码哦～
		$tmpRst['content'] = $tmpInfo;
		curl_close($curl); // 关闭CURL会话
		return $tmpRst; // 返回数据
	}
	/**
	 * Send a Get Request
	 * Returns false on failure
	 * @param string The URL You want to access
	 * @param string The reference URL You want to show
	 * @param array The cookies you want to set
	 * @param array Datas(Params) you want to put in the address
	 * @access public
	 * @return array {'code'=>HTTP_Stat, 'content'=>Content}
	 */
	public static function getFromAddr($url, $ref, $cookie = null, $data = null){
		$curl = curl_init(); // 启动一个CURL会话
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		if(!empty($cookie)){
			curl_setopt($curl, CURLOPT_COOKIE, $cookie);
		}
		curl_setopt($curl, CURLOPT_REFERER, $ref);// 设置Referer
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		$tmpInfo = curl_exec($curl); // 执行操作
		if (curl_errno($curl)) {
		   return false;//捕抓异常
		}
		$tmpRst['code'] = curl_getinfo($curl,CURLINFO_HTTP_CODE); //获取HTTP状态嘛
		$tmpRst['content'] = $tmpInfo;
		curl_close($curl); // 关闭CURL会话
		return $tmpRst; // 返回数据
	}
}
class BoostPHP_MySQLClass{
	/**
	 * Connect to a database using mysqli
	 * returns false on failure
	 * @param string Username for mysql database
	 * @param string Password for mysql database
	 * @param string Database Name for mysql database
	 * @param string Hostname for mysql database
	 * @param int Port Number for mysql database
	 * @access public
	 * @return Mysql Connection
	 */
	public static function connectDB($Username, $Password, $Database, $Host = "127.0.0.1", $Port = 3306){
		$MySQLiConn = mysqli_connect($Host, $Username, $Password, $Database, $Port);
		return $MySQLiConn;
	}
	
	/**
	 * Select datas from the Database
	 * returns false on failure
	 * @param MySQLi Connection Data
	 * @param string The statement you want to query for selection, need to be prevented from SQL Injection
	 * @access public
	 * @return array
	 * @returnKey count[int] - how many results can be shown
	 * @returnKey result[array] - the result of the selection(only when count > 0)
	 */
	public static function selectIntoArray_FromStatement($MySQLiConn, $SelectStatement){
		$SelectRST = mysqli_query($MySQLiConn, $SelectStatement);
		
		if(!$SelectRST){
			return false;
		}
		$Selectcount = mysqli_num_rows($SelectRST);
		$ResultArr['count'] = $Selectcount;
		$ResultArr['result'] = array();
		/* Old(PHP<5.3)
		$SelectTempArr = array();
		if($Selectcount>0){
			for($xh = 0; $xh < $Selectcount; $xh++){
				$SelectTempArr = mysqli_fetch_array($SelectRST);
				$ResultArr['result'][] = $SelectTempArr;
			}
		}
		*/
		if($Selectcount>0){
		    $ResultArr['result'] = mysqli_fetch_all($SelectRST,MYSQLI_ASSOC);
		}
		mysqli_free_result($SelectRST);
		return $ResultArr;
	}
	
	/**
	 * Select datas from the Database
	 * returns false on failure
	 * @param MySQLi Connection Data
	 * @param string Table name,  need to be prevented from SQL Injection
	 * @param array The array that requirements should fit, should be like array(Key=>Value, Key1=>Value1)
	 * @param array The array of keys to be the key for ordering
	 * @param int The limit number you want to select, -1 means to select all
	 * @param int the offset you want to start with, by default it is 0, which means from the start.
	 * @access public
	 * @return array
	 * @returnKey count[int] - how many results can be shown
	 * @returnKey result[array] - the result of the selection(only when count > 0)
	 */
	public static function selectIntoArray_FromRequirements($MySQLiConn, $Table, $SelectRequirement = array(), $OrderByArray = array(), $NumLimit = -1, $OffsetNum = 0){
	    $SelectState = "SELECT * FROM " . $Table;
		
		if(!empty($SelectRequirement)){
			$SelectXH = 0;
			$SelectState .= " WHERE ";
			foreach($SelectRequirement as $BLName=>$BLValue){
				if($SelectXH == 0){
					$SelectState .= mysqli_real_escape_string($MySQLiConn,$BLName) . " = '" . mysqli_real_escape_string($MySQLiConn,$BLValue) . "'";
				}else{
					$SelectState .= " AND " . mysqli_real_escape_string($MySQLiConn,$BLName) . " = '" . mysqli_real_escape_string($MySQLiConn,$BLValue) . "'";
				}
				$SelectXH++;
			}
		}
		if(!empty($OrderByArray)){
			$SelectXH = 0;
			$SelectState .= " ORDER BY ";
			foreach($OrderByArray as $BLName){
				if($SelectXH == 0){
					$SelectState .= mysqli_real_escape_string($MySQLiConn, $BLName);
				}else{
					$SelectState .= ", " . mysqli_real_escape_string($MySQLiConn, $BLName);
				}
				$SelectXH++;
			}
		}
		if($NumLimit != -1){
		    $SelectState .= " LIMIT " . $NumLimit;
		}
		if($OffsetNum > 0){
		    $SelectState .= " OFFSET " . $OffsetNum;
		}
		$MRST=mysqli_query($MySQLiConn,$SelectState);
		if(!$MRST){
			return false;
		}
		$Selectcount = mysqli_num_rows($MRST);
		$ResultArr['count'] = $Selectcount;
		$ResultArr['result'] = array();
		/* Old (<PHP 5.3)
		$SelectTempArr = array();
		if($Selectcount>0){
			for($xh = 0; $xh < $Selectcount; $xh++){
				$SelectTempArr = mysqli_fetch_array($MRST);
				$ResultArr['result'][] = $SelectTempArr;
			}
		}
		*/
		if($Selectcount>0){
		    $ResultArr['result'] = mysqli_fetch_all($MRST,MYSQLI_ASSOC);
		}
		mysqli_free_result($MRST);
		return $ResultArr;
	}
	/**
	 * Check data exists that fits requirements from the Database
	 * returns false on failure
	 * @param MySQLi Connection Data
	 * @param string The table you want to query for selection, need to be prevented from SQL Injection
	 * @param array The array that requirements should fit, should be like array(Key=>Value, Key1=>Value1)
	 * @access public
	 * @return int - how many results can be shown
	 */
	public static function checkExist($MySQLiConn,$Table,$SelectRequirement){
		$SelectState = "SELECT COUNT(*) FROM " . $Table;
		if(!empty($SelectRequirement)){
			$SelectXH = 0;
			$SelectState .= " WHERE ";
			foreach($SelectRequirement as $BLName=>$BLValue){
				if($SelectXH == 0){
					$SelectState .= mysqli_real_escape_string($MySQLiConn,$BLName) . " = '" . mysqli_real_escape_string($MySQLiConn,$BLValue) . "'";
				}else{
					$SelectState .= " AND " . mysqli_real_escape_string($MySQLiConn,$BLName) . " = '" . mysqli_real_escape_string($MySQLiConn,$BLValue) . "'";
				}
				$SelectXH++;
			}
		}
		$MRST=mysqli_query($MySQLiConn,$SelectState);
		if(!$MRST){
			return false;
		}
		$MyArr = mysqli_fetch_array($MRST);
		$MyRSTNum = $MyArr['COUNT(*)'];
		mysqli_free_result($MRST);
		return $MyRSTNum;
	}
	/**
	 * Insert data into the DB
	 * returns false on failure
	 * @param MySQLi Connection Data
	 * @param string The table you want to query for selection, need to be prevented from SQL Injection
	 * @param array The array that you want the insert value to be, should be like array(Key=>Value, Key1=>Value1)
	 * @access public
	 * @return boolean - true if successful
	 */
	public static function insertRow($MySQLiConn, $Table, $InsertArray){
		if(empty($InsertArray)){
			return false;
		}
		$InsertStatement = "INSERT INTO " . $Table . " ";
		$NameState = "(";
		$ValueState = "(";
		$InsertXH = 0;
		foreach ($InsertArray as $BLName=>$BLValue){
			if($InsertXH == 0){
				$NameState .= mysqli_real_escape_string($MySQLiConn,$BLName);
				$ValueState .= "'" . mysqli_real_escape_string($MySQLiConn, $BLValue) . "'";
			}else{
				$NameState .= ', ' . mysqli_real_escape_string($MySQLiConn,$BLName);
				$ValueState .= ", '" . mysqli_real_escape_string($MySQLiConn,$BLValue) . "'";
			}
			$InsertXH++;
		}
		$NameState .= ")";
		$ValueState .= ")";
		$InsertStatement .= $NameState . " VALUES " . $ValueState;
		$InsertRST = mysqli_query($MySQLiConn,$InsertStatement);
		return ((!$InsertRST) ? false : true);
	}
	/**
	 * Update the Table of the MYSQL DB
	 * returns false on failure
	 * @param MySQLi Connection Data
	 * @param string The table you want to query for selection, need to be prevented from SQL Injection
	 * @param array The array that you want to update your value, like array(Key=>Value, Key1=>Value1)
	 * @param array The array that requirements should fit, should be like array(Key=>Value, Key1=>Value1)
	 * @access public
	 * @return boolean - if succeed, return true.
	 */
	public static function updateRows($MySQLiConn, $Table, $UpdateArray, $SelectRequirement){
		if(empty($UpdateArray)){
			return false;
		}
		$UpdateState = "UPDATE " . $Table . " SET ";
		$UpdateXH = 0;
		foreach($UpdateArray as $BLName=>$BLValue){
			if($UpdateXH != (count($UpdateArray) - 1)){
				$UpdateState .= mysqli_real_escape_string($MySQLiConn,$BLName) . " = '" . mysqli_real_escape_string($MySQLiConn, $BLValue) . "', ";
			}else{
				$UpdateState .= mysqli_real_escape_string($MySQLiConn,$BLName) . " = '" . mysqli_real_escape_string($MySQLiConn, $BLValue) . "' ";
			}
			$UpdateXH++;
		}
		if(!empty($SelectRequirement)){
			$UpdateXH = 0;
			$UpdateState .= "WHERE ";
			foreach($SelectRequirement as $BLName=>$BLValue){
				if($UpdateXH == 0){
					$UpdateState .= mysqli_real_escape_string($MySQLiConn, $BLName) . " = '" . mysqli_real_escape_string($MySQLiConn, $BLValue) . "'";
				}else{
					$UpdateState .= " AND " . mysqli_real_escape_string($MySQLiConn, $BLName) . " = '" . mysqli_real_escape_string($MySQLiConn, $BLValue) . "'";
				}
				$UpdateXH++;
			}
		}
		$UpdateRST = mysqli_query($MySQLiConn,$UpdateState);
		return ((!$UpdateRST) ? false : true);
	}
	/**
	 * Delete Rows from MYSQL DB
	 * returns false on failure
	 * @param MySQLi Connection Data
	 * @param string The table you want to query for selection, need to be prevented from SQL Injection
	 * @param array The array that requirements should fit, should be like array(Key=>Value, Key1=>Value1)
	 * If the third param is empty, it will clear the entire table.
	 * @access public
	 * @return boolean - if succeed, return true.
	 */
	public static function deleteRows($MySQLiConn, $Table, $SelectRequirement){
		$DeleteStatement = "DELETE FROM " . $Table;
		if(!empty($SelectRequirement)){
			$DeleteStatement .= " WHERE ";
			$DeleteXH = 0;
			foreach($SelectRequirement as $BLName=>$BLValue){
				if($DeleteXH == 0){
					$DeleteStatement .= mysqli_real_escape_string($MySQLiConn, $BLName) . " = '" . mysqli_real_escape_string($MySQLiConn, $BLValue) . "'";
				}else{
					$DeleteStatement .= " AND " . mysqli_real_escape_string($MySQLiConn, $BLName) . " = '" . mysqli_real_escape_string($MySQLiConn, $BLValue) . "'";
				}
				$DeleteXH++;
			}
		}
		$DeleteRST = mysqli_query($MySQLiConn,$DeleteStatement);
		return ((!$DeleteRST) ? false : true);
	}
	/**
	 * Close a MySQL Connection
	 * @param MySQLi Connection Data
	 * @return Always true
	 */
	public static function closeConn($MySQLiConn){
		mysqli_close($MySQLiConn);
		return true;
	}
}
class BoostPHP_FileClass{
	/**
	 * Open A file and reads its content
	 * returns false on failure
	 * @param string Your filename to read
	 * @param int Start Position you want to read, negative values means to read from the last * characters from the end.
	 * @param int Length of the content you want to read, -1 means to read all
	 * @access public
	 * @return string The content of the file
	 */
	public static function getFileContent($FileName,$Start=0,$Length=-1){
		if(!file_exists($FileName)){ return false; }
		$MyFile = fopen($FileName, "r");
		if(!$MyFile){return false;}
		$MyFileSize = filesize($FileName);
		//SEEK_CUR:设置指针位置为当前位置加上第二个参数所提供的offset字节。
		//SEEK_END:设置指针位置为EOF加上offset字节。在这里，offset必须设置为负值。
		//SEEK_SET:设置指针位置为offset字节处。这与忽略第三个参数whence效果相同。
		if(($Start > $MyFileSize) || ($Start + $Length > $MyFileSize)){return false;}
		if($Start>=0){fseek($MyFile,$Start,SEEK_SET);}else{fseek($MyFile,$Start,SEEK_END);}
		if($Length==-1){$Length = $MyFileSize - $Start;}
		$MyFileContent = fread($MyFile,$Length);
		fclose($MyFile);
		return $MyFileContent;
	}
	/**
	* Put contents into a file
	* returns false on failure
	* @param string Filename to write
	* @param string Content to write
	* @access public
	* @return int Number of chars written
	*/
	public static function putFileContent($FileName, $Content){
		$MyFile = fopen($FileName,"w");
		if(!$MyFile){return false;}
		$WriteStatus = fwrite($MyFile,$Content);
		if($WriteStatus === false){return false;}
		fclose($MyFile);
		return $WriteStatus;
	}
	/**
	* Append contents into a file
	* returns false on failure
	* @param string Filename to write
	* @param string Content to write
	* @access public
	* @return int Number of chars written
	*/
	public function addFileContent($FileName,$Content){
		$MyFile = fopen($FileName,"a");
		if(!$MyFile){return false;}
		$WriteStatus = fwrite($MyFile,$Content);
		if($WriteStatus === false){return false;}
		fclose($MyFile);
		return $WriteStatus;
	}
	/**
	* Deal with the file that users uploaded
	* returns false on failure
	* @param string POSTED Value Name
	* @param string Where to put the upload file?
	* @param array Allowed extension, * means everyextent
	* @param int FileSize in BYTES to be allowed, 0 means to allow any
	* @access public
	* @return boolean true when succeed
	*/
	public static function dealUploadFile($UploadName, $PutTo, $AllowedExt = array("*"), $AllowedSize = 0){
		if(empty($_FILES[$UploadName])){return false;}
		$MyFile = $_FILES[$UploadName];
		if($MyFile['error']>0){return false;}
		$TempExtArr = explode(".",$MyFile['name']);
		$FileExension = end($TempExtArr);
		if($MyFile['size'] > $AllowedSize && $AllowedSize != 0){
			return false;
		}
		$FindedExt = false;
		foreach($AllowedExt as $TempAExt){
			if($TempAExt == $FileExension || $TempAExt == "*"){
				$FindedExt = true;
			}
		}
		if(!$FindedExt){return false;}
		if(file_exists($PutTo)){
			if(!unlink($PutTo)){return false;}
		}
		move_uploaded_file($MyFile['tmp_name'],$PutTo);
		return true;
	}
	/**
	* Get the original name of the uploaded file
	* returns false on failure
	* @param string POSTED Value Name
	* @access public
	* @return string the original name
	*/
	public static function getUploadFileOriginalName($UploadName){
		if(!empty($_FILE[$UploadName])){return $_FILE[$UploadName]['name'];}else{return false;}
	}
	/**
	* Get the original extension of the uploaded file
	* returns false on failure
	* @param string POSTED Value Name
	* @access public
	* @return string the original extension
	*/
	public static function getUploadFileOriginalExt($UploadName){
		if(!empty($_FILE[$UploadName])){return end(explode(".",$_FILE[$UploadName]['name']));}else{return false;}
	}
	
}
class BoostPHP_SecureClass_SHA{
	
	/**
	* Encode the string using SHA256 Encoding Method(With Salt)
	* @param string $Text String to be encode with
	* @param string $Salt Optional, The Salt to be add together
	* @access public
	* @return string The encoded string
	*/
	public static function SHA256Encode($Text,$Salt = ""){
		if(!empty($Salt)){$Salt=md5($Salt);}
		return hash("sha256",$Text . $Salt);
	}
	
	/**
	* Encode the string using SHA512 Encoding Method(With Salt)
	* @param string $Text String to be encode with
	* @param string $Salt Optional, The Salt to be add together
	* @access public
	* @return string The encoded string
	*/
	public static function SHA512Encode($Text, $Salt = ""){
		if(!empty($Salt)){$Salt=md5($Salt);}
		return hash("sha512",$Text . $Salt);
	}
	
	/**
	* Encode the string using SHA1 Encoding Method(With Salt)
	* @param string $Text String to be encode with
	* @param string $Salt Optional, The Salt to be add together
	* @access public
	* @return string The encoded string
	*/
	public static function SHA1Encode($Text, $Salt = ""){
		if(!empty($Salt)){$Salt=md5($Salt);}
		return hash("sha1",$Text . $Salt);
	}
}

?>