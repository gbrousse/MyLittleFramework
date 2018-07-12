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
 * Formatted common handler 
 * Stock formatted logs in an array 
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\Logs\Handlers
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */
class FormattedCommonHandler extends CommonHandler{
    use Strings;
    
    /**
     * Pattern to format logs
     *
     * @var string
     */
    private $logPattern;
    /**
     * Pattern to format the logs container
     *
     * @var string
     */
    private $containerPattern;
    /**
     * Pattern to format backtrace
     *
     * @var string
     */
    private $tracePattern;
    /**
     * Constructor
     * @param string $logPattern Pattern to format logs
     * @param string $containerPattern Pattern to format the logs container
     * @param string $tracePattern Pattern to format backtrace
     * @return void
     */
    public function __construct($logPattern = '<li class="{level}">{time} - {message}<br><ul>{backtrace}</ul></li>',$containerPattern = '<ul>{loglist}</ul>',$tracePattern = '<li>{trace}</li>') {
        $this->logPattern = $logPattern;
        $this->containerPattern = $containerPattern;
         $this->tracePattern = $tracePattern;
    }
    /**
     * Show logs
     * @param string $level log level
     * @return void
     */
    public function show($level = null) {
        $logs = $this->get($level);
        $replace = array('loglist'=>'','backtrace'=>'');
        foreach ($logs as $log){
            $replace['level'] = $log['level'];
            $replace['message'] = $log['message']; 
            $replace['time'] = $log['time'];
            $replace['loglist'] .= $this->replaceInString($this->logPattern,$replace);
            if($log['backtrace']){
                foreach($log['backtrace'] as $trace){
                    $replace['backtrace'] .= $this->replaceInString($this->tracePattern,array('trace'=>$trace));
                }
            }
        }
        echo $this->replaceInString($this->containerPattern,$replace);
    }
    
    
}
