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
 * Description of model
 *
 * @author gregory
 */
abstract class Model implements ModelInterface{
    use LoggerAwareTrait;
    
    protected $db = null;
    
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

    public function load($id) {
        
    }

    public function save() {
        
    }
    
    public function delete() {
        
    }

}
