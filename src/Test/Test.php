<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Test;

/**
 * Description of Test
 *
 * @author gregory
 */
class Test{
    use \Core\Common\Logs\LoggerAwareTrait;
    function test1(){
        $this->logger->alert('alert test1');
        $this->test12();

    }

    function test12(){
        $this->logger->error('{test} {test1}',array('test'=>'error','test1'=>'test12'));
    }

    function test2(){
        $this->logger->debug('debug test 2');
    }
}


