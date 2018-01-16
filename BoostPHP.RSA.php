<?php
/**
* RSA Encryption and Decryption Class
* Author: Windy(www.xsyds.cn)
*/
class BoostPHP_SecureClass_RSAKey{
	public $privateKey = "";
	public $publicKey = "";
}
class BoostPHP_SecureClass_RSA
{
    
	/**
	* Generate new RSA Private and Public Key for single communication
	* @param int Bits for the key
	* @access public
	* @return BoostPHP_SecureClass_RSAKey KeyPair of RSA
	* You can access the key by the variable $privateKey and $publicKey
	*/
    public static function generateNewKeys($keybit = 1024)
    {
		$Conf = array(
				"digest_alg" => "sha512",
				"private_key_bits" => $keybit,
				"private_key_type" => OPENSSL_KEYTYPE_RSA);
        $resource = openssl_pkey_new($Conf);
		$MKey = new BoostPHP_SecureClass_RSAKey();
        openssl_pkey_export($resource, $MKey->privateKey);
        $detail = openssl_pkey_get_details($resource);
        $MKey->publicKey = $detail['key'];
		return $MKey
    }
	
	/**
	* Encrypt the data by the Public Key given(Usually this function is used by the client)
	* @param string $data The data to be encrypted
	* @param string $publicKey The public key used during encryption
	* @access public
	* @return string Encrypted Data
	*/
    public static function publicEncrypt($data, $publicKey)
    {
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return $encrypted;
    }
	
	/**
	* Decrypt the data by the Public Key given(Usually this function is used by the client)
	* @param string $data The data to be decrypted
	* @param string $publicKey The public key used during decryption
	* @access public
	* @return string Decrypted Data
	*/
    public static function publicDecrypt($data, $publicKey)
    {
        openssl_public_decrypt($data, $decrypted, $publicKey);
        return $decrypted;
    }
	
	/**
	* Encrypt the data by the Private Key given(Usually this function is used by the server)
	* @param string $data The data to be encrypted
	* @param string $privateKey The private key used during encryption
	* @access public
	* @return string Encrypted Data
	*/
    public static function privateEncrypt($data, $privateKey)
    {
        openssl_private_encrypt($data, $encrypted, $privateKey);
        return $encrypted;
    }
	
	/**
	* Decrypt the data by the Private Key given(Usually this function is used by the server)
	* @param string $data The data to be Decrypted
	* @param string $privateKey The private key used during Decryption
	* @access public
	* @return string Decrypted Data
	*/
    public static function privateDecrypt($data, $privateKey)
    {
        openssl_private_decrypt($data, $decrypted, $privateKey);
        return $decrypted;
    }
}
?>