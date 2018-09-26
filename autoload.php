<?php
class Loader 
{

    public static $library;

    protected static $classPath = __DIR__ . "/Controllers/";

    protected static $interfacePath = __DIR__ . "/Controllers/";

    public function __construct($requireInterface = true) 
    {
        if(!isset(static::$library)) {
            // Get all files inside the class folder
            foreach(array_map('basename', glob(static::$classPath . "*.php", GLOB_BRACE)) as $classExt) {
                // Make sure the class is not already declared
                if(!in_array($classExt, get_declared_classes())) {
                    // Get rid of php extension easily without pathinfo
                    $classNoExt = substr($classExt, 0, -4); 
                    $file = static::$path . $classExt;

                    if($requireInterface) {
                        // Get interface file
                        $interface = static::$interfacePath . $classExt;
                        // Check if interface file exists
                        if(!file_exists($interface)) {
                            // Throw exception
                            die("Unable to load interface file: " . $interface);
                        }

                        // Require interface
                        require_once $interface;
                        //Check if interface is set
                        if(!interface_exists("Interface" . $classNoExt)) {
                            // Throw exception
                            die("Unable to find interface: " . $interface);
                        }
                    }

                    // Require class
                    require_once $file;
                    // Check if class file exists
                    if(class_exists($classNoExt)) {
                        // Set class        // class.container.php
                        static::$library[$classNoExt] = new $classNoExt();
                    } else {
                        // Throw error
                        die("Unable to load class: " . $classNoExt);
                    }

                }
            }
        }
    }

    /*public function get($class) 
    {
        return (in_array($class, get_declared_classes()) ? static::$library[$class] : die("Class <b>{$class}</b> doesn't exist."));
    }*/
}