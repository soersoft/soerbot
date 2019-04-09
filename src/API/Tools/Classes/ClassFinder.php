<?php  
namespace API\Tools;

class ClassFinder implements IClassFinder
{
    /**
     * finder all registred classes are implemens interfaceName
     * thans to
     * - https://stackoverflow.com/questions/3993759/php-how-to-get-a-list-of-classes-that-implement-certain-interface
     * needs check for abstract class:
     * - https://www.php.net/manual/en/reflectionclass.isabstract.php
     * 
     * @property $interfaceName 
     * - name of looking interface\class (IClassFinder::class)
     * @return List<Type>
     * - type implement or instance of interfaceName
     */
    public static function findClasses(string $interfaceName)
    {
        $classes = array_filter(
            get_declared_classes(),
            function( $className ) use ( $interfaceName ) {
                return in_array( $interfaceName, class_implements( $className ) );
            }
        );

        // Check for abstract class
        // https://www.php.net/manual/en/reflectionclass.isabstract.php
        // https://stackoverflow.com/questions/7131295/dynamic-class-names-in-php
        $classesAbstract = array();
        foreach($classes as $class)
        {
            $className = get_class($class);
            $testClass = new ReflectionClass($ClassName);
            if ($testClass->isAbstract())
                $classesAbstract[] = $class;
        }
        \array_diff($classes, $classesNot4Factory);

        return $classes;
    }
}