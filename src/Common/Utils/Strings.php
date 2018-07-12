<?php
/*
 * This file is part of MyLittleFramework.
 *
 * (c) Grégory Brousse <pro@gregory-brousse.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Common\Utils;
/**
 * tools to work with strings 
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\Utils
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */
trait Strings {
    /**
     * Encrypt a string 
     * @uses element Description
     * @param string $string string to encrypt
     * @param string $salt salt use to encrypt string
     * @param string $encryptionType encryption type (sha256,md5,etc...)
     * @return string encrypt string
     */
    protected function encrypt($string,$salt,$encryptionType = 'sha256'){
        return hash($encryptionType, $salt.$string);
    }
    
    /**
     * Replace shortcodes by their value in a string
     * 
     * @param string $string string with shortcodes
     * @param array $datas array of the values to insert in the string
     * @param string $openDelimiter beginning of the shortcode
     * @param string $closeDelimiter end of the shortcode
     * @return string string with shortcodes replaced
     * 
     * @example $this->replaceInString('line [[line]]',array('line',1),'[[',']]'), will return 'line 1'
     */
    protected function replaceInString($string,$datas,$openDelimiter = '{',$closeDelimiter = '}'){
        $replace = array();
        foreach ($datas as $strToFind => $replacement) {
            
            if (is_array($replacement) || (is_object($replacement) && !method_exists($replacement, '__toString'))) {
                $replacement = serialize($replacement);
            }
            $replace[$openDelimiter.$strToFind.$closeDelimiter] = $replacement;
        }
        return strtr($string, $replace);
    }
    
    
}
