<?php

/*
 * This file is part of MyLittleFramework.
 *
 * (c) Grégory Brousse <pro@gregory-brousse.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core\Common\Logs\handlers;

/**
 * Common handler 
 * Stock logs in an array 
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\Logs\Handlers
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */
class CommonHandler implements HandlerInterface {
    
    /**
     * List of the logs
     *
     * @var array
     */
    private $logs = array();
    
    /**
     * add a log
     * @param string $level log level
     * @param string $message log text
     * @param array|bool $backtrace log backtrace
     * @return void
     */
    public function write($level, $message, $backtrace) {
        $this->logs[]=array(
            'time'=>date('H:i'),
            'level'=>$level,
            'message'=>$message,
            'backtrace'=>$backtrace
        );
    }
    
    /**
     * get list of logs
     * @param string|null $level specify a level of log to return 
     * @return array list of logs
     */
    public function get($level = null){
        if($level){
            return $this->getLevel($level);
        }
        return $this->logs;
    }
    
    /**
     * get list of logs for a specific level
     * @param string|null $level specify a level of log to return 
     * @return array list of logs
     */
    public function getLevel($level){
        $logs = array();
        foreach ($this->logs as $time => $log) {
            if($log['level']==$level)$logs[$time]=$log;
        }
        return $log;
    }
    
    /**
     * show logs
     * @param string|null $level specify a level of log to show
     * @return void
     */
    public function show($level = null){
        $logs = $this->get($level);
        foreach ($logs as $time => $log){
            echo '['.$log['time'].'] ['.$log['level'].'] '.$log['message'].'<br/>';
            if($log['backtrace']){
                foreach ($log['backtrace'] as $trace) {
                    echo '-- '.$trace.'<br/>';
                }
            }
        }
    }
}
