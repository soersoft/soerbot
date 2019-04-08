<?php

use \API\Common;

namespace \API\Tools;

class InstancesStorage
{
    /**
     * list of instances
     */
    protected $instances = array();
    /**
     * clear list of instances
     */
    public function clearInstances()
    {
        self::$instances = array();
        gc_collect_cycles(); // GC Should kill old ones
    }
    /**
     * refresh list of instances
     */
    public function refreshInstances(IFactory $factory)
    {
        if (!($factory instanceof IFactory))
            throw new UnexpectedValueException();

        $classes = $factory->scan();
        $this->clearInstances();
        $this->$instances = $factory->createIntances($classes);
    }
}