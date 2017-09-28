<?php

/**
* AES Encryption and Decryption Class
* Original Author hushangming(www.jbxue.com)
* Modification: Windy(www.xsyds.cn)
*/

class BoostPHP_SecureClass_AES{
	private $_bit = MCRYPT_RIJNDAEL_256;
	private $_type = MCRYPT_MODE_ECB;
	private $_iv_size = null;
	private $_iv = null;
	
	/**
	* @param int $_bit Bit to encode, support 128 ,192, 256
	* @param string $_type Encode, Decode Way (cfb,cbc,nofb,ofb,stream,ecb)
	*/
	public function __construct($_bit = 256, $_type = 'ecb'){
		// 加密字节
		if(192 === $_bit){
			$this->_bit = MCRYPT_RIJNDAEL_192;
		}elseif(128 === $_bit){
			$this->_bit = MCRYPT_RIJNDAEL_128;
		}elseif(256 === $bit){
			$this->_bit = MCRYPT_RIJNDAEL_256;
		}
		// 加密方法
		if('cfb' === $_type){
			$this->_type = MCRYPT_MODE_CFB;
		}elseif('cbc' === $_type){
			$this->_type = MCRYPT_MODE_CBC;
		}elseif('nofb' === $_type){
			$this->_type = MCRYPT_MODE_NOFB;
		}elseif('ofb' === $_type){
			$this->_type = MCRYPT_MODE_OFB;
		}elseif('stream' === $_type){
			$this->_type = MCRYPT_MODE_STREAM;
		}else{
			$this->_type = MCRYPT_MODE_ECB;
		}
		if($this->_type !== MCRYPT_MODE_ECB){
			//计算向量
			$this->_iv_size = mcrypt_get_iv_size($this->_bit, $this->_type);
			$this->_iv = mcrypt_create_iv($this->_iv_size, MCRYPT_RAND);
		}
	}
	
	/**
	* Encrypt the String to AES
	* @param string $string String to be encoded
	* @param string $password Encription Password
	* @return string
	*/
	public function encryptAES($string,$password){
		if(MCRYPT_MODE_ECB === $this->_type){
			$encodeString = mcrypt_encrypt($this->_bit, $password, $string, $this->_type); 
		}else{
			$encodeString = mcrypt_encrypt($this->_bit, $password, $string, $this->_type, $this->_iv);
		}
		return $encodeString;
	}
	
	/**
	* Decode the AES String
	* @param string $string String to be decoded
	* @param string $password Decryption Password
	* @return string
	*/
	public function decryptAES($string,$password){
		$string = $this->toHexString($string);
		if(MCRYPT_MODE_ECB === $this->_type){
			$decodeString = mcrypt_decrypt($this->_bit, $password, $string, $this->_type);
		}else{
			$decodeString = mcrypt_decrypt($this->_bit, $password, $string, $this->_type, $this->_iv);
		}
		return $decodeString;
	}
	
	/**
	* Change the String to from Normal String to HEX Code
	* @param string $string
	* @return stream
	*/
	private function toHexString ($string){
		$buf = "";
		for ($i = 0; $i < strlen($string); $i++){
			$val = dechex(ord($string{$i}));
		if(strlen($val)< 2)
			$val = "0".$val;
			$buf .= $val;
		}
		return $buf;
	}
	
	/**
	* Change the String from Hex to Normal String
	* @param stream $string
	* @return string
	*/
	private function fromHexString($string){
		$buf = "";
		for($i = 0; $i < strlen($string); $i += 2){
			$val = chr(hexdec(substr($string, $i, 2)));
			$buf .= $val;
		}
		return $buf;
	}
}
