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
 * Log Handler Interface
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\Logs\Handlers
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */
interface HandlerInterface {
    public function write($level,$message,$backtrace);
}
