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

    /**
     * Loads the data from all the features.
     * @return Collection
     */
    public function load()
    {
        return $this->features->each(function ($feature) {
            $feature->load();
        });
    }

    /**
     * Saves the data to all the features.
     * @return Collection
     */
    public function save()
    {
        return $this->features->each(function ($feature) {
            $feature->save();
        });
    }

    /**
     * Returns feature.
     * @param $featureName
     * @return Feature|null
     */
    public function get($featureName): ?Feature
    {
        if (!($this->features instanceof Collection)) {
            return null;
        }

        return $this->features->get($featureName);
    }

    /**
     * Alias for get.
     * @param string $featureName
     * @return Feature
     */
    public function feature(string $featureName): Feature
    {
        return $this->get($featureName);
    }

    /**
     * Adds feature to the collection.
     * @param string $featureName
     * @param Feature $feature
     * @return Collection
     */
    public function addFeature(string $featureName, Feature $feature)
    {
        return $this->features->set($featureName, $feature);
    }

    /**
     * Returns all the features.
     * @return Collection|null
     */
    public function all(): ?Collection
    {
        return $this->features;
    }
}
