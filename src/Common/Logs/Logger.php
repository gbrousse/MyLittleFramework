<?php

/*
 * This file is part of MyLittleFramework.
 *
 * (c) Grégory Brousse <pro@gregory-brousse.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core\Common\Logs;

use \Psr\Log\AbstractLogger;
use \Psr\Log\LogLevel;
use \Core\Common\Logs\handlers\HandlerInterface;
use \Core\Common\Utils\Strings;

/**
 * Logger class
 *
 * Simple logger compliant with psr-3
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\logs
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */

class Logger extends AbstractLogger{
    
    use Strings;
    
    /**
     * Min level to log
     *
     * @var string
     * @see \Psr\Log\LogLevel
     */
    private $logLevel;
    
    /**
     * Minimum level from which to add backtrace to log
     *
     * @var string
     * @see \Psr\Log\LogLevel
     */
    private $backtraceLevel;
    
    /**
     * list of the logs handlers
     *
     * @var array
     */
    private $handlers;
    
    /**
     * Log Levels for comparison
     * @var array
     */
    private $logLevels = array(
        LogLevel::EMERGENCY => 7,
        LogLevel::ALERT     => 6,
        LogLevel::CRITICAL  => 5,
        LogLevel::ERROR     => 4,
        LogLevel::WARNING   => 3,
        LogLevel::NOTICE    => 2,
        LogLevel::INFO      => 1,
        LogLevel::DEBUG     => 0
    );
    
    /**
     * Constructor
     * @param string $minLevel logging minimum level 
     * @param string $backtraceLevel Minimum level from which to add backtrace to log
     * @return void
     * @see \Psr\Log\LogLevel
     */
    public function __construct($minLevel = LogLevel::DEBUG,$backtraceLevel = LogLevel::EMERGENCY) {
        $this->logLevel = $minLevel;
        $this->backtraceLevel = $backtraceLevel;
        $commonHandler = new handlers\CommonHandler();
        $this->attachHandler($commonHandler, 'common');
    }
    
    /**
     * attach an handler to logger
     * @param HandlerInterface $handler log handler instance
     * @param string $name name to identify the handler
     * @param string $minLevel logging min level for the handler
     * @param string $dedicateToLevel dedicate the handler to this level
     * @return void
     */
    public function attachHandler(HandlerInterface $handler, $name, $minLevel = LogLevel::WARNING,$dedicateToLevel = false){
        $this->detachHandler('common');
        $this->handlers[$name] = array(
            'handler'=>$handler,
            'min'=>$minLevel,
            'lvl'=>$dedicateToLevel
        );    
    }
    /**
     * detach an handler from logger
     * @param string $name name to identify the handler
     * @return bool return true if handler have been detached, false if not.
     */
    public function detachHandler($name){
        if($this->hasHandler($name)){
            unset($this->handlers[$name]);
            return true;
        }
        return false;
    }
    
    /**
     * test if handler is attached
     * @param string $name name to identify the handler
     * @return bool return true if handler is attached, false if not.
     */
    public function hasHandler($name){
        return isset($this->handlers[$name]);
    }
    
    /**
     * execute the handlers with log infos
     * @param string $level level of the log
     * @param string $formattedMessage log text
     * @param array $formattedBacktrace backtrace or false 
     * @return void
     */
    private function runHandler($level,$formattedMessage,$formattedBacktrace){
        foreach ($this->handlers as $handler) {
            if($handler['lvl']){
                if($handler['lvl']==$level){
                    $handler['handler']->write($level,$formattedMessage,$formattedBacktrace);
                }
            }elseif($this->logLevels[$handler['min']] <= $this->logLevels[$level]){
                $handler['handler']->write($level,$formattedMessage,$formattedBacktrace);
            }
        }
    }
    
    /*
     * retreive a specifique handler
     * @param string $name name to identify the handler
     * @return \Core\common\logs\HandlerInterface|bool return the handler or false
     */
    public function getHandler($name){
        return ($this->hasHandler($name))? $this->handlers[$name]['handler']:false;
    }
    
    /*
     * retreive a specifique handler (magic method)
     * @param string $name name to identify the handler
     * @return \Core\common\logs\HandlerInterface return the handler or false
     */
    public function __get($name) {
        return $this->getHandler($name);
    }
    
    
    /**
     * Handle logs
     * @param string $level log level
     * @param string $message log text
     * @param array $context log context
     * @return void
     */
    public function log($level, $message, array $context = array()) {
        
        if ($this->logLevels[$this->logLevel] > $this->logLevels[$level]) {
            return false;
        }
        $backtrace = $this->getBackTrace();
        $context['function'] = 'main';
        if(isset($backtrace[0])){
            if(isset($backtrace[0]['class'])){
                $context['function'] = $backtrace[0]['class'];
            }
            
            if(isset($backtrace[0]['function'])){
                $context['function'] .= '::'.$backtrace[0]['function'];
            }
        }
        $formattedMessage = $this->replaceInString('{function} : '.$message,$context);
        
        if ($this->logLevels[$this->backtraceLevel] <= $this->logLevels[$level]) {
            $formattedBacktrace = $this->formatBacktrace($backtrace);
        }else{
            $formattedBacktrace = null;
        }
        $this->runHandler($level,$formattedMessage,$formattedBacktrace);   
    }
    
    /**
     * recover bactrace 
     * @return array backtrace
     */
    private function getBackTrace(){
        $backTrace = array();
        $reflect = new \ReflectionClass(__CLASS__);
     
        foreach (debug_backtrace (DEBUG_BACKTRACE_PROVIDE_OBJECT) as $trace) {
            if(isset($trace['class']) && ($trace['class']==$reflect->getName() || $trace['class']==$reflect->getParentClass()->getName()))continue;
            $backTrace[] = $trace; 
        }
        return $backTrace;
    }
    
    /**
     * format the bactrace 
     * @return array formatted backtrace
     */
    private function formatBacktrace($backtrace){
        $formattedBacktrace = array();
        foreach ($backtrace as $trace) {
            $function = ((isset($trace['class']))?$trace['class'].':':'').$trace['function'];
            $formattedBacktrace[]=sprintf("%s at l.%d : %s", $trace['file'],$trace['line'],$function);
        }
        return $formattedBacktrace;
    }
    
}
