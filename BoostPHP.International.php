<?php

/**
* International Language Support
* Author: Windy(www.xsyds.cn)
* Usage:
$MyInternationalClass = new BoostPHP_InternationalClass();
$CustomLangSupport = $MyInternationalClass->getSupportedLanguage();
$MyLangURL = $MyInternationalClass->matchSupportedLanguage($CustomLangSupport, array("zh-cn"=>"http://www.kvm.ink/zh-cn/", "en"=>"http://www.kvm.ink/en/"), "http://www.kvm.ink/en/");
* MyLangURL is your URL to jumpTo, you can use BoostPHP_ResultClass to jump to your URL.
*/
require 'BoostPHP.main.php'; //This file will not be required twice, because it has selfcheck inside the file. This statement is just to make sure that this file is required properly.
class BoostPHP_InternationalClass{
	/**
	 * Get supported language from
	 * @access public
	 * @return array - Supported Languages
	 */
	public function getSupportedLanguage(){
		$acceptLangHeader = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		//正常格式: Accept-Language: zh-cn,zh;q=0.5, PHP会自动去掉Accept-Language: 头, q是权重的意思, 就是多希望用最前面的语言, 若Q为0则说明不支持的语言
		//这里不读取Q, 发送0的太少了
		$acceptLangArray = explode(",",$acceptLangHeader);
		if(count($acceptLangArray) != 0 && !empty($acceptLangArray)){
			$LastItem = explode(";",$acceptLangArray[count($acceptLangArray)-1]);
			$acceptLangArray[count($acceptLangArray)-1] = $LastItem[0];
		}
		return $acceptLangArray;
	}
	
	/**
	 * Match Custom Supported Language with Website Supported Language
	 * returns Default URL when not matched
	 * @param string $SupportedLanguage the language array that custom client support, usually gained by getSupportedLanguage() function
	 * @param array $WebsiteLanguageList The language=>URL array that your websites supports.
	 * @param string $DefaultURL The default URL if any of these arrays does not match
	 * @access public
	 * @return string - the URL you want to forward your client to
	 */
	public function matchSupportedLanguage($SupportedLanguage, $WebsiteLanguageList, $DefaultURL){
		foreach($SupportedLanguage as $mSupportLang){
			foreach($WebsiteLanguageList as $mWebsiteLang=>$mWebsiteURL){
				if(strtolower($mSupportLang) == strtolower($mWebsiteLang)){
					return $mWebsiteURL;
				}
			}
		}
		return $DefaultURL;
	}
}

?>