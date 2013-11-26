<?php
class Utility{
    const CHAR_MIX = 0;
    const CHAR_NUM = 1;
    const CHAR_WORD = 2;
	
    /**
     * 生成随机字符串
     * @param number $len 长度
     * @param number $type 生成字符类型 
     * @return string 随机字串
     */
	public static function GenRandomStr($len = 6,$type = CHAR_MAX){
		$random = '';
		for ($i = 0; $i < $len;  $i++) {
			switch ($type){
				case self::CHAR_NUM:
					if (0==$i) {
						$random .= chr(rand(49, 57));
					} else {
						$random .= chr(rand(48, 57));
					}
					break;
				case self::CHAR_WORD:
					$random .= chr(rand(65, 90));
					break;
				case self::CHAR_MIX:
					if ( 0==$i ){
						$random .= chr(rand(65, 90));
					} else {
						$random .= (0==rand(0,1))?chr(rand(65, 90)):chr(rand(48,57));
					}
					break;
			}
		}
		return $random;
	}
}