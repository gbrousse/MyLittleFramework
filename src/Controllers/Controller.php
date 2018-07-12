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

namespace Core\Controllers;
use \Psr\Container\ContainerInterface;

/**
 * Description of ControllerAbstract
 *
 * @author gregory
 */
class Controller implements ControllerInterface{
    use \Core\Common\Logs\Loggable;
    use \Core\Common\Utils\Navigation;
  
    protected $services;
    
    public function __construct($options,  ContainerInterface $services){
        $this->services = $services;
        
        if($this->services->logger){
            $this->setLogger($this->services->logger);
            $this->_logger->debug('Logger attached',array(__METHOD__));
        }
        $this->run($options);
    }
    
    
    protected function loadView($options,$loadCats = true,$showHeader = true, $showFooter = true){
        $this->logDebug('init with options : '.  print_r($options,true));
        $view = new \Core\Views\View($this->services);
        
        
        if($loadCats){
            $categories = new \Core\Models\CategoriesCollection($this->services->db,$this->services->logger);
            $categories->load();
            $options['categories']=$categories;
            $this->logDebug('categories loaded ');
        }
        $options['showHeader']=$showHeader;
        $options['showFooter']=$showFooter;
        $options['breadcrumb']=$this->services->router->getBreadCrumb();
        
        
        
        $view->setOptions($options);
        
        return $view;
    }

}
