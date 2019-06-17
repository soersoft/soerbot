<?php

namespace SoerBot\Classes;

use CharlotteDunois\Yasmin\Utils\Collection;

class User
{
    /**
     * @var Collection
     */
    protected $features = null;

    /**
     * @var string
     */
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
        $this->features = new Collection();
    }

    /**
     * Returns name of the user.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Adds feature to the collection.
     * @param string $featureName
     * @param $feature
     */
    public function addFeature(string $featureName, $feature)
    {
        $this->features->set($featureName, $feature);
    }

    /**
     * Looks for a necessary method in users features.
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        foreach ($this->features->all() as $k => $feature) {
            if (method_exists($feature, $name)) {
                return $feature->{$name}(...$arguments);
            }
        }
    }
}
