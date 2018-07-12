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

namespace Core\Common\Logs;

/**
 * Description of Loggable
 *
 * @author gregory
 */
trait LoggableTrait {
    protected $_logger = null;
    public function setLogger(\Psr\Log\LoggerInterface $logger){
        $this->_logger = $logger;
    }
    protected function getContext(){
        return array(get_class($this),debug_backtrace()[2]['function']);     
    }
    
    protected function logError($message){
        if($this->_logger)$this->_logger->error($message,$this->getContext());
        if($this->_logger)$this->_logger->error(implode(chr(13),$this->getBacktrace()),$this->getContext());
    }
    
    protected function logWarning($message){
        if($this->_logger)$this->_logger->warning($message,$this->getContext());
    }
    
    protected function logDebug($message){
        if($this->_logger)$this->_logger->debug($message,$this->getContext());
    }
	
    protected function logInfo($message){
        if($this->_logger)$this->_logger->info($message,$this->getContext());
    }
	
    protected function getBacktrace(){
            $backtrace = debug_backtrace(1,5);
            $return = array();
            foreach($backtrace as $traceInfo){
                    $func = $traceInfo['function'].'()';
                    if($traceInfo['class']!=''){
                            $func = $traceInfo['class'].'->'.$func;
                    }
                    $return[]=$traceInfo['file'].' line '.$traceInfo['line'].' : '.$func;

            }
            return $return;
    }
}
