<?php

namespace API\Common;

interface IFactory
{
    /**
     * get all classes, implements
     * - IPostSender
     */
    public function scan():array;
    /**
     * createIntances of all found casses 
     */
    public function createIntances(array $classes):array;
}