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

namespace Core\Common\Conf;

use \Exception;

/**
 * Description of config
 *
 * @author gregory
 */
class Config {
    protected $datas = null;
    
    public function __construct($path){
        if(file_exists($path)){
            $this->datas = $this->toObject(parse_ini_file($path,true));
        }else{
            throw new Exception ('config file not found');
        }
    }
    
    public function get($key){
        
        if(property_exists($this->datas, $key)){
           
            return $this->datas->$key;
        }else{
            throw new Exception ("config element $key not found");
        }
    }
    
    public function __get($key){
        return $this->get($key);
    }
    
    private function toObject($array){
        $return = new \stdClass();
        foreach($array as $key => $value){
            $return->$key = (is_array($value))?$this->toObject($value):$value;
        }
        return $return;
    }
}
