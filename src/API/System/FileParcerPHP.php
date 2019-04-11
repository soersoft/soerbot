<?php

namespace API\System
{
    /**
     * Parce PHP file, using PHP tokens, to get:
     * - nameSpases
     *  - Classes in namespaces
     *  - Interfaces in namespaces
     * thanks to:
     * - https://stackoverflow.com/questions/7153000/get-class-name-from-file
     * see also about PHP tokens:
     * - https://www.php.net/manual/en/tokens.php
     */
    class FileParcerPHP
    {
        /**
         * get the full name (name \ namespace) of a class from its file path
         * result example: (string) "I\Am\The\Namespace\Of\This\Class"
         *
         * @param $filePathName
         *
         * @return  string
         */
        public function getClassFullNameFromFile($filePathName)
        {
            return $this->getClassNamespaceFromFile($filePathName) . '\\' . $this->getClassNameFromFile($filePathName)[0];
        }


        /**
         * build and return an object of a class from its file path
         *
         * @param $filePathName
         *
         * @return  mixed
         */
        public function getClassObjectFromFile($filePathName)
        {
            $classString = $this->getClassFullNameFromFile($filePathName)[0];

            $object = new $classString;

            return $object;
        }


        /**
         * get the class namespace form file path using token
         *
         * @param $filePathName
         *
         * @return  null|string
         */
        public function getNamespaceFromFile($filePathName)
        {
            $src = file_get_contents($filePathName);

            $tokens = token_get_all($src);
            $count = count($tokens);
            $i = 0;
            $namespace = '';
            $namespace_ok = false;
            while ($i < $count) {
                $token = $tokens[$i];
                if (is_array($token) && $token[0] === T_NAMESPACE) {
                    // Found namespace declaration
                    while (++$i < $count) {
                        if ($tokens[$i] === ';') {
                            $namespace_ok = true;
                            $namespace = trim($namespace);
                            break;
                        }
                        $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                    }
                    break;
                }
                $i++;
            }
            if (!$namespace_ok) {
                return null;
            } else {
                return $namespace;
            }
        }

        /**
         * get the class name form file path using token
         * - T_CLASS
         * 
         * @param $filePathName
         *
         * @return  mixed
         */
        public function getClassNameFromFile($filePathName)
        {
            $php_code = file_get_contents($filePathName);

            $classes = array();
            $tokens = token_get_all($php_code);
            $count = count($tokens);
            for ($i = 2; $i < $count; $i++) {
                if ($tokens[$i - 2][0] == T_CLASS
                    && $tokens[$i - 1][0] == T_WHITESPACE
                    && $tokens[$i][0] == T_STRING
                ) {

                    $class_name = $tokens[$i][1];
                    $classes[] = $class_name;
                }
            }

            return $classes;
        }

        /**
         * get the abstract class name form file path using token
         * - T_ABSTRACT
         * 
         * @param $filePathName
         *
         * @return  mixed
         */
        public function getAbstractClassNameFromFile($filePathName)
        {
            $php_code = file_get_contents($filePathName);

            $abstractClasses = array();
            $tokens = token_get_all($php_code);
            $count = count($tokens);
            for ($i = 2; $i < $count; $i++) {
                if ($tokens[$i - 2][0] == T_ABSTRACT
                    && $tokens[$i - 1][0] == T_WHITESPACE
                    && $tokens[$i][0] == T_STRING
                ) {

                    $class_name = $tokens[$i][1];
                    $abstractClasses[] = $class_name;
                }
            }

            return $abstractClasses;
        }

        /**
         * get the interface name form file path using token
         * - T_INTERFACE
         * 
         * @param $filePathName
         *
         * @return  mixed
         */
        public function getInterfaceNameFromFile($filePathName)
        {
            $php_code = file_get_contents($filePathName);

            $interfaces = array();
            $tokens = token_get_all($php_code);
            $count = count($tokens);
            for ($i = 2; $i < $count; $i++) {
                if ($tokens[$i - 2][0] == T_INTERFACE
                    && $tokens[$i - 1][0] == T_WHITESPACE
                    && $tokens[$i][0] == T_STRING
                ) {

                    $class_name = $tokens[$i][1];
                    $interfaces[] = $class_name;
                }
            }

            return $interfaces;
        }

        /**
         * get the
         * - class name
         * - abstract class name
         * - interface name
         * 
         * @param $filePathName
         *
         * @return array mixed
         * - class name
         * - abstract class name
         * - interface name
         */
        public function getAnyDeclarationsFromFile($filePathName)
        {
            $res = array_merge(
                $this->getClassNameFromFile($filePathName),
                $this->getAbstractClassNameFromFile($filePathName),
                $this->getInterfaceNameFromFile($filePathName)
              );
            return $res;
        }
    }
}