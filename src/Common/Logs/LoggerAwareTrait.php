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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Log Aware Trait
 *
 * Use to implement logging to a class
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\logs
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */
trait LoggerAwareTrait {
    /**
     * The logger instance.
     *
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * Sets a logger.
     * @param LoggerInterface $logger logger instance
     * @return void
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        if($logger){
            $this->logger = $logger;
        }else{
            $this->logger = new NullLogger();
        }
    }
    
}
