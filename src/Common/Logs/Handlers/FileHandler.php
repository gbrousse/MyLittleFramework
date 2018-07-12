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

use \Core\Common\Utils\Strings;

/**
 * File handler
 * Stock logs in a rotating file
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\Logs\Handlers
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */
class FileHandler implements HandlerInterface{
    
    use Strings;
    
    /**
     * Path of the directory where log files have to be written
     *
     * @var string
     */
    protected $path;
    /**
     * Number of days handler will retain log files
     *
     * @var int
     */
    protected $retaining;
    /**
     * identifier insert in the filename
     *
     * @var string
     */
    protected $fileName;
    /**
     * Pattern to format logs
     *
     * @var string
     */
    protected $logPattern;
    
    /**
     * Constructor
     * @param string $path Path of the directory where log files have to be written
     * @param int $retaining Number of days handler will retain log files
     * @param string $fileName identifier insert in the filename
     * @param string $logpattern Pattern to format logs
     * @return void
     */
    public function __construct($path,$retaining = 5,$fileName = 'logs',$logpattern = '[{time}] [{level}] {message}'){
        $path = realpath($path);
        if(!is_dir($path)){
            throw new Exception('invalid path');
            return false;
        }elseif(!is_writable($path)){
            throw new Exception('path isn\'t writable' );
            return false;
        }
        echo $path;
        $this->path = $path;
        $this->retaining = $retaining;
        $this->fileName = $fileName;
        $this->logPattern = $logpattern;
        $this->clean();
    }
    /**
     * add a log
     * @param string $level log level
     * @param string $message log text
     * @param array|bool $backtrace log backtrace
     * @return void
     */
    public function write($level, $message, $backtrace) {
        $filepath = $this->getFilePath($level);
        $log = $this->replaceInString($this->logPattern,array('level'=>$level,'time'=>date('H:i'),'message'=>$message));
        error_log($log.PHP_EOL, 3, $filepath);
        if($backtrace){
            foreach ($backtrace as $trace) {
                error_log($trace.PHP_EOL, 3, $filepath);
            }
        }
    }
    /**
     * Get the cuurent log file path
     * @param string $level specify level (not used in this handler)
     * @return log file path
     */
    protected function getFilePath($level){
        return $this->path.DIRECTORY_SEPARATOR.date('Ymd').'-'.$this->fileName.'.log';
    }
    /**
     * Get the log files path pattern
     * @return log files path pattern
     */
    protected function getFilePathPattern(){
        return $this->path.DIRECTORY_SEPARATOR.'*-'.$this->fileName.'.log';
        
    }
    /**
     * Delete old logs
     * @return void
     */
    protected function clean(){
        $logFilesList = glob($this->getFilePathPattern());
        foreach($logFilesList as $logFilePath){
            if((time()-filemtime($logFilePath)) > ($this->retaining*24*3600))unlink ($logFilePath);
        }
    }
    
    
}
