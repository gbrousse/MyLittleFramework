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

namespace Core\Models;
use \Core\Common\Logs\LoggerAwareTrait;
use \Exception;

/**
 * Description of collection
 *
 * @author gregory
 */
abstract class Collection implements CollectionInterface {
    use LoggerAwareTrait;
    
    protected $db = null;
    protected $collection = array();
    
    public function __construct($db=null,$logger=null) {
        if($logger){
            $this->setLogger($logger);
            $this->logger->debug('Logger attached');
        }
        if(!$db){
            $this->logger->error('No DB');
            throw new Exception(__CLASS__.' : No DB');
        }
        $this->db = $db;
    }

    public function add($element) {
        $this->collection[$element->id] = $element;
    }
    
    public function delete($id = null) {
        if(is_null($id)){
            foreach ($this->collection as &$col) {
                $col->delete();
                unset($col);
            }
        }else{
            if(isset($this->collection[$id])){
                if(method_exists($this->collection[$id], 'delete')){
                    $this->collection[$id]->delete();
                    unset($this->collection[$id]);
                }
            }
        }
        
        return false;
    }

    public function load($ids = null) {
        
    }

    public function save() {
        foreach ($this->collection as $element) {
            if(method_exists($element, 'save')){
                $element->save();
            }
        }
    }
    
    public function getElement($id){
        if(isset($this->collection[$id]))return $this->collection[$id];
        return false;
    }
    
    public function getElements(){
        return $this->collection;
    }

}
