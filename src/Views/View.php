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
namespace Core\Views;
/**
 * Description of viewsdata
 *
 * @author gregory
 */
class View {
    private $options;
    private $viewFilesPath = './www/';
    private $viewHeaderFile = 'header.html';
    private $viewFooterFile = 'footer.html';
    private $viewFile = 'index.html';
    
    private $services = null;
    
    private $title = '';
    private $description = '';
    private $keywords = '';
    
    
    public function __construct($services) {
        $this->services = $services;
    }
    
    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function getKeywords() {
        return $this->keywords;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    function getOptions() {
        return $this->options;
    }
    function getOption($lbl) {
        return (isset($this->options[$lbl]))?$this->options[$lbl]:false;
    }

    function getViewFilesPath() {
        return $this->viewFilesPath;
    }

    function getViewHeaderFile() {
        return $this->viewFilesPath.$this->viewHeaderFile;
    }

    function getViewFooterFile() {
        return $this->viewFilesPath.$this->viewFooterFile;
    }

    function getViewFile() {
        return $this->viewFilesPath.$this->viewFile;
    }
    
    function getServices(){
        return $this->services;
    }

    function setOptions($options) {
        $this->options = $options;
    }
    
    function addOption($lbl,$value){
        $this->options[$lbl]=$value;
    }

    function setViewFilesPath($viewFilesPath) {
        $this->viewFilesPath = $viewFilesPath;
    }

    function setViewHeaderFile($viewHeaderFile) {
        $this->viewHeaderFile = $viewHeaderFile;
    }

    function setViewFooterFile($viewFooterFile) {
        $this->viewFooterFile = $viewFooterFile;
    }

    function setViewFile($viewFile) {
        $this->viewFile = $viewFile;
    }
    
    function render(){
        $view = $this;
        if(is_file($this->getViewHeaderFile()))include $this->getViewHeaderFile();
        include $this->getViewFile();
        if(is_file($this->getViewFooterFile()))include $this->getViewFooterFile();
    }


}
