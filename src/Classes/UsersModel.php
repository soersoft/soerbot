<?php

namespace SoerBot\Classes;

use CharlotteDunois\Yasmin\Utils\Collection;

class UsersModel
{
    /**
     * @var Collection
     */
    protected $features = null;

    public function __construct()
    {
        $this->features = new Collection();
    }

    public function load()
    {
        return $this->features->each(function ($feature) {
            $feature->load();
        });
    }

    public function save()
    {
        return $this->features->each(function ($feature) {
            $feature->save();
        });
    }

    public function feature(string $featureName): Feature
    {
        return $this->get($featureName);
    }

    public function get($featureName): ?Feature
    {
        if (!($this->features instanceof Collection)) {
            return null;
        }

        return $this->features->get($featureName);
    }

    public function addFeature(string $featureName, Feature $feature)
    {
        return $this->features->set($featureName, $feature);
    }

    public function all(): ?Collection
    {
        return $this->features;
    }
}
