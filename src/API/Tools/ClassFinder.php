<?php  

namespace API\Tools;

use API\Send\{MailSenderExample1, MailSenderExampleNot4Factory1};

class ClassFinder implements IClassFinder
{
    /**
     * DOESN'T WORK NEEDS to force loading classes
     * - Like this
     *  - https://stackoverflow.com/questions/36676074/php-how-to-get-all-classes-when-using-autoloader
     * finder all registred classes are implemens interfaceName
     * thans to
     * - https://stackoverflow.com/questions/3993759/php-how-to-get-a-list-of-classes-that-implement-certain-interface
     * needs check for abstract class:
     * - https://www.php.net/manual/en/reflectionclass.isabstract.php
     * load classes
     * - composer dump-autoload -o
     * 
     * Check for abstract class
     * -   // https://www.php.net/manual/en/reflectionclass.isabstract.php
     * -   // https://stackoverflow.com/questions/7131295/dynamic-class-names-in-php
     * -   // https://www.php.net/manual/ru/class.reflectionclass.php
     * 
     * @property $interfaceName 
     * - name of looking interface\class (IClassFinder::class)
     * @return List<Type>
     * - type implement or instance of interfaceName
     */
    public static function findClasses(string $interfaceName)
    {
        // To get classes which are implements this interface
        $classesFullNames = array_filter(
            get_declared_classes(),
            function( $className ) use ( $interfaceName ) {
                return in_array( $interfaceName, class_implements( $className ) );
            }
        );

        // To check for abstract class
        $classesAbstract = array();
        foreach($classesFullNames as $className)
        {
            $reflectiontestClass = new \ReflectionClass($className);
            if ($reflectiontestClass->isAbstract())
                $classesAbstract[] = $reflectiontestClass->getName();
        }
        $classesFullNames = \array_diff($classesFullNames, $classesAbstract);

        return $classesFullNames;
    }
}