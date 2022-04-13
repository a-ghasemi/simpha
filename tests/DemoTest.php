<?php

use \PHPUnit\Framework\TestCase;

class DemoTest extends TestCase
{
    public function testTrue(){
        //Arrange
        $content = 'This is a test log';

        //Act

        //Assert
        $this->assertTrue(is_string($content));

        //Get Back

    }


}