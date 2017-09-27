<?php
/**
* RSA Encryption and Decryption Class
* Author: Windy(www.xsyds.cn)
*/
class BoostPHP_SecureClass_RSA
{
    public $privateKey = '';

    public $publicKey = '';
    
	/**
	* Generate new RSA Private and Public Key for single communication
	* @access public
	* @return void
	* You can access the key by the variable $privateKey and $publicKey
	*/
    public function generateNewKeys()
    {
        $resource = openssl_pkey_new();
        openssl_pkey_export($resource, $this->privateKey);
        $detail = openssl_pkey_get_details($resource);
        $this->publicKey = $detail['key'];
    }
	
	/**
	* Encrypt the data by the Public Key given(Usually this function is used by the client)
	* @param string $data The data to be encrypted
	* @param string $publicKey The public key used during encryption
	* @access public
	* @return string Encrypted Data
	*/
    public function publicEncrypt($data, $publicKey)
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
    public function publicDecrypt($data, $publicKey)
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
    public function privateEncrypt($data, $privateKey)
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
    public function privateDecrypt($data, $privateKey)
    {
        openssl_private_decrypt($data, $decrypted, $privateKey);
        return $decrypted;
    }
}
?>