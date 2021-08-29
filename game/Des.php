<?php
/**
 * Title：DES加解密类
 * desc：可与java的DES(DES/CBC/PKCS5Padding)加密方式兼容，兼容php7.1以上版本。
 * User: 元宝支付（18pay.net）
 * Date: 2021/01/01
 * Time: 12:30
 */
class Des{
   
    public function __construct(){} 
    /**
     *
     * 加密函数
     * 算法：des
     * 加密模式：ecb
     * 补齐方法：PKCS5
     *
     * @param unknown_type $input
     */
    public function encrypt($input, $key)
    {
		//由于php7.1废弃了mcrypt_* 一系列函数，所以采用openssl版本
		$str = $this->pkcsPadding($input, 8);
		$key = str_pad($key, 8, '0'); //3DES加密将8改为24
		$sign = @openssl_encrypt($str, 'DES-ECB', $key,OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);
		//转为base64，可以有效解决乱码等问题
		$sign = base64_encode($sign);
		return $sign;
    }
    
    /**
     * 解密函数
     * 算法：des
     * 加密模式：ecb
     * 补齐方法：PKCS5
     * @param unknown_type $input
     */
    public function decrypt($input, $key)
    {
		//由于php7.1废弃了mcrypt_* 一系列函数 所以采用openssl版本
		$encrypted = base64_decode($input);
		$key = str_pad($key, 8, '0'); //3DES加密将8改为24
		$sign = @openssl_decrypt($encrypted, 'DES-ECB', $key,OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);
		$sign = $this->unPkcsPadding($sign);
		$sign = rtrim($sign);
		return $sign;
    }
	/**
     * 填充
     *
     * @param $text
     * @param $blocksize
     * @return string
     */
    private function pkcsPadding($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
 
     /**
     * 去填充
     * 
     * @param $text
     * @return string
     */
    private function unPkcsPadding($text)
    {
		$pad = ord($text {strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, - 1 * $pad);
    }
    
}