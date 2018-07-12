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
 * File extend handler
 * Stock logs in a rotating file by level
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\Logs\Handlers
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */
class ExtendFileHandler extends FileHandler {
    /**
     * Get the cuurent log file path for a specified level
     * @param string $level specify level
     * @return log file path
     */
    protected function getFilePath($level) {
        return $this->path.DIRECTORY_SEPARATOR.date('Ymd').'-'.$level.'-'.$this->fileName.'.log';
    }
}
