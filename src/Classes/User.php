<?php

namespace App\Implementations;

use App\Implementations\Features\Feature;
use CharlotteDunois\Yasmin\Utils\Collection;

class User
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Collection
     */
    protected $features;

    /**
     * User constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->features = new Collection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addFeature(string $name, Feature $feature)
    {
        return $this->features->set($name, $feature);
    }

    public function getFeature(string $name)
    {
        return $this->features->has($name) ? $this->features->get($name) : null;
    }

    public function removeFeature(string $name)
    {
        return $this->features->has($name) ? $this->features->delete($name) : null;
    }
}
