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

use \Psr\Container\ContainerInterface;
use \ReflectionClass;

/**
 * Service Locator
 *
 * Service Locato compliant with psr-11
 * 
 * @package MyLittleFramework
 * @subpackage Core\Common\Container
 * @author Grégory Brousse <pro@gregory-brousse.fr>
 * 
 */
class ServiceLocator implements ContainerInterface{
    
    
    
    /**
     * List of the services
     *
     * @var array
     */
    protected $_services = array();
    
    
    /**
     * Get a service
     * @param string $id service identifier
     * @return object|false return the asked service or false if the service doesn't exist
     */
    public function get($id) {
        if ($this->has($id)) {
            return $this->_services[strtolower($id)];
        }
        throw new ServiceLocatorNotFoundException("Service $id is not configured yet");
        return false;
    }
    /**
     * Check if the service locator contain a specified service
     * @param string $id service identifier
     * @return bool
     */
    public function has($id) {
        if (isset($this->_services[strtolower($id)])) {
            return true;
        }
        return false;
    }
    /**
     * Add a service to the locator
     * @param string $id service identifier
     * @param obj|string service service instance or callback
     * @return Core\Common\Container\ServiceLocator return itself
     */
    public function add($id,$service){
        if(!is_string($id)){
            throw new ServiceLocatorException("First parameter must be a string");
            return false;
        }
        if(!(is_object($service) || is_callable($service))){
            throw new ServiceLocatorException("Second parameter must be an object or a function");
            return false;
        }
        $this->_services[strtolower($id)]=$instance;
        return $this;
    }
    
    /**
     * create a service from class+parameters and add it to the locator
     * @param string $id service identifier
     * @param string Class to instantiate
     * @param array parameters for the instanciation
     * @return void
     */
    public function createFromClass($id,$class,$options){
        if(!is_string($id)){
            throw new ServiceLocatorException("First parameter must be a string");
            return false;
        }
        if(!class_exists($class)){
            throw new ServiceLocatorException("Second parameter must be a class");
            return false;
        }
        $reflect = new ReflectionClass($class);
        $this->add($id, $reflect->newInstanceArgs($options));      
    }
    
    
    /**
     * Get an object service (magic method)
     * @param string $id service identifier
     * @return object|false return the service or false if it doesn't exist
     */
    public function __get($id){ 
        $service = $this->get($id);
        if(is_callable($service)){
            throw new ServiceLocatorException("Service $id is a function, it must be called");
            return false;
        }
        return $this->get($id);
    }
    /**
     * Call an callback service (magic method)
     * @param string $id service identifier
     * @return mixed|false return the service return or false if it doesn't exist
     */
    public function __call($id,$options){ 
        $service = $this->get($id);
        if(is_object($service)){
            throw new ServiceLocatorException("Service $id is an object, you must use get method");
            return false;
        }
        return call_user_func_array($service, $options);
    }

}
