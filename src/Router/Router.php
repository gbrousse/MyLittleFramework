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
namespace Core\router;

use \Core\Common\Logs\Loggable;
/**
 * Description of router
 *
 * @author gregory
 */
class Router {
    use Loggable;
    //put your code here
    protected $routes;
    protected $base;
    protected $breadcrumb;
    
    public function __construct($params,$logger=null) {
        if($logger){
            $this->setLogger($logger);
            $this->_logger->debug('Logger attached',array(__METHOD__));
        }
        
        $this->base = $params->base;
        
        if(!file_exists($params->path)){
            if($this->_logger)$this->_logger->error('Routes file doesn\'t exists',array(__METHOD__));
            throw new Exception ('routes file not found');
        }
        
        if($this->routes = json_decode(file_get_contents($params->path))){
            if($this->_logger)$this->_logger->debug('Routes loaded',array(__METHOD__));
        }else{
            if($this->_logger)$this->_logger->error('Routes can\'t be loaded',array(__METHOD__));
            throw new Exception ('routes not loaded');
        }
    }
    
    public function getController ($path=null){
        if(!$path)$path = str_replace($this->base,'',$_SERVER['REQUEST_URI']);
        if($this->_logger)$this->_logger->debug('Path : '.$path,array(__METHOD__));
  
        return $this->HandlePath(urldecode($path));
    }
    
    protected function handlePath($path,$routes = null,$options = array()){
        if(!$routes){
            $routes = $this->routes;
            if($path != $this->base.'/'){
                $this->addIndextoBreadcrumb();
            }
        }
        foreach($routes as $route => $infos){
            if(preg_match('/'.$infos->route.'/', $path, $matches)){
                $this->logDebug('Route found : '.$route.' ('.$path.' - '.$infos->route.')'); 
                $nextPath = preg_replace('/'.preg_quote($matches[0], '/').'/', '', $path, 1);
                $this->logDebug('Next path : '.$nextPath);
                if(isset($infos->options)){
                    foreach($infos->options as $optionLabel => $optionValue){
                        if(preg_match('/\$(\d*)/', $optionValue,$optionCell)){
                            
                            $options[$optionLabel]=$matches[intval($optionCell[1])];
                        }else{
                           
                            $options[$optionLabel]=$optionValue;
                        }
                        $this->logDebug('New option : '.$optionLabel.' = '.$options[$optionLabel]);
                    }  
                }
                
                $this->addRouteToBreadcrumb($infos, $route, $options);
                if(isset($infos->subroutes)){
                    $this->logDebug('Sub routes found ');
                    return $this->handlePath($nextPath, $infos->subroutes, $options);
                }
                if(isset($infos->controller)){
                    $this->logDebug('Controller : '.$infos->controller);
                    return array(
                        'controller'=>$infos->controller,
                        'options'=>$options
                    );
                }else{
                    $this->logError('No controller found for path : '.$path);
                    return false;
                }
            }
        }
    }
    
    public function getPath($route,$options=array()){
        $path='';
        $replacementValues = array();
        $currentRoute = $this->routes;
        if(!is_array($route)){
            $route = array($route);
        }
        foreach($route as $routePart){
            $this->logDebug('Handle sub route : '.$routePart);
            if(isset($currentRoute->$routePart))$currentRoute = $currentRoute->$routePart;
            elseif(isset($currentRoute->subroutes->$routePart))$currentRoute = $currentRoute->subroutes->$routePart;
            else {
                $this->logError($routePart.' not found in '.  print_r($currentRoute,true));
                continue;
            }
            $path .= $currentRoute->route;
            $this->logDebug('New path :'.$path);
            if(isset($currentRoute->options)){
               
                
                foreach($currentRoute->options as $optionLabel => $optionValue){
                    if(preg_match('/\$(\d*)/', $optionValue,$optionCell)){
                        if(!isset($options[$optionLabel])){
                            $this->logError('Parameter :'.$optionLabel.'not found');
                            continue;
                        }
                    }
                }   
            }
        }
        $path = preg_replace_callback('/(\(\?<(\w+)>[^)]*\))/', 
                              function($m) use($options) { 
                                    return $options[$m[2]]; 
                              },
                              $path);
        $this->logDebug('Final path :'.$path);
        return $this->base.str_replace('\\', '', $path);
    }
    private function addIndextoBreadcrumb(){
        $this->logDebug('adding index to breadcrumb');
        $name = $this->routes->index->name;
        $path = $this->getPath(array('index'), array());
        $this->breadcrumb['links'][]=array('name'=>$name,'path'=>$path);
    }
    
    private function addRouteToBreadcrumb($route,$routeId,$options){
        $this->logDebug('adding '.$routeId.' to breadcrumb');
        if($routeId == 'default')return;
        if(isset($route->name)){
            $name = $route->name;
        }elseif (isset($route->subroutes->default->name)) {
            $name = $route->subroutes->default->name;
        }else{
            
            $name = 'undefined';
        }
        foreach($options as $key => $value){
            $name = str_replace("[$key]", $value, $name);
        }
        if(isset($this->breadcrumb['historic']) && is_array($this->breadcrumb['historic'])){
            $this->breadcrumb['historic'][]=$routeId;
        }else{
            $this->breadcrumb['historic']=array($routeId);
        }
        if(isset($this->breadcrumb['options']) && is_array($this->breadcrumb['options'])){
            $this->breadcrumb['options']=  array_merge($this->breadcrumb['options'],$options);
        }else{
            $this->breadcrumb['options']=$options;
        }
        $path = $this->getPath($this->breadcrumb['historic'], $this->breadcrumb['options']);
        $this->breadcrumb['links'][]=array('name'=>$name,'path'=>$path);
        $this->logDebug(print_r($this->breadcrumb,true));
    }
    
    public function getBreadCrumb(){
        return $this->breadcrumb['links'];
    }
    
}
