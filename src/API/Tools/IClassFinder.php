<?php  

namespace API\Tools;

interface IClassFinder
{
    /**
     * @property $interfaceName 
     * - name of looking interface\class (IClassFinder::class)
     * @return List<Type>
     * - type implement or instance of interfaceName
     */
    function findClasses(string $interfaceName);
}