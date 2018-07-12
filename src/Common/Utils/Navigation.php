<?php

/*
 * Copyright (C) 2018 gregory
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Core\Common\Utils;

/**
 * Description of navigation
 *
 * @author gregory
 */
trait Navigation {
    
    protected $HTTPErrors = array(
        400=>"Bad Request",
        401=>"Unauthorized",
        402=>"Payment Required",
        403=>"Forbidden",
        404=>"Not Found",
        405=>"Method Not Allowed",
        406=>"Not Acceptable",
        407=>"Proxy Authentication Required",
        408=>"Request Timeout",
        409=>"Conflict",
        410=>"Gone",
        411=>"Length Required",
        412=>"Precondition Failed",
        413=>"Request Entity Too Large",
        414=>"Request-URI Too Long",
        415=>"Unsupported Media Type",
        416=>"Requested Range Not Satisfiable",
        417=>"Expectation Failed",
        500=>"Internal Server Error",
        501=>"Not Implemented",
        502=>"Bad Gateway",
        503=>"Service Unavailable",
        504=>"Gateway Timeout",
        505=>"HTTP Version Not Supported",
    );
    
    protected function _goTo($url){
		$url = 'http://'.str_replace('//','/',$_SERVER['HTTP_HOST'].$url);
        $this->logDebug('redirect : '.$url);
        header('location:'.$url);
    }
    protected function _testUrl($url,&$error){
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if($httpCode <= 399) {
            $return = true;
        }else{
            $return = false;
            $error = (($httpCode<=499)?'Client error :':'Server error :').$this->HTTPErrors[$httpCode];
        }
        curl_close($handle);
        return $return;
    }
}
