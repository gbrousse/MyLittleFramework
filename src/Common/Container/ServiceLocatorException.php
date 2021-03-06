<?php

/*
 * This file is part of MyLittleFramework.
 *
 * (c) Grégory Brousse <pro@gregory-brousse.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core\Common\Container;

use \Psr\Container\ContainerExceptionInterface;
use \Exception;

/**
 * Service Locator exception
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\Container
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */
class ServiceLocatorException extends Exception implements ContainerExceptionInterface {
    //put your code here
}
