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

namespace Core\Common\db;

use \Core\Common\Logs\LoggerAwareTrait;
use \PDO;

/**
 * Description of dbpdo
 *
 * @author gregory
 */
class DbPDO extends PDO{
    use LoggerAwareTrait;
    
    public function __construct($params,$logger=null) {
        if(!isset($params->host) || !isset($params->name) || !isset($params->login) || !isset($params->pass) ){
            $this->logger->error('Connection to DB error',array(__METHOD__));
        }
        $dsn = 'mysql:dbname='.$params->name.';host='.$params->host;
        parent::__construct($dsn,$params->login,$params->pass);
        try{ 
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        }catch (PDOException $e){
            $this->logger->error('PDO error : '.$e->getMessage(),array(__METHOD__));
            return;
        }
        $this->logger->debug('Connection to DB',array(__METHOD__));   
    }   
}
