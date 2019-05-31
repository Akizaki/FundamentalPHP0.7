<?php

/**
 * Description of Loader
 * 
 */
require_once 'Core/Exception/ClassUndefinedException.class.php';
require_once 'Core/Utility/Logger.class.php';
require_once 'Core/Underscore.class.php';

/**
 * 
 */
class Loader {

    protected $_dirs;
    protected $_extensions;
    protected $_methods;
    protected $_logger;

    /**
     * 
     * @param array $dirs
     * @param array $extensions
     * @throws ClassUndefinedException
     */
    public function __construct(array $dirs, array $extensions)
    {
        $this->_logger = new Logger();
        if (!class_exists("Underscore"))
        {
            $this->_logger->write("class Underscore is not defined,[need require Underscore.class.php]");
            throw new ClassUndefinedException("Underscore", "[need require Underscore.class.php]");
        }
        $this->_dirs = new Underscore($dirs);
        $this->_extensions = new Underscore($extensions);
        $this->_methods = new Underscore(get_class_methods($this));
    }

    /**
     * 
     * @return boolean
     */
    public function register()
    {

        $get_loaders = function ($method) {
            return strpos($method, "load") === 0;
        };

        $autoload = function ($loader) {
            return is_callable([$this, $loader]) ? spl_autoload_register([$this, $loader]) : false;
        };

        return $this->_methods->filter($get_loaders)->every($autoload);
    }

    /**
     * 
     * @param type $file_path
     * @return boolean
     */
    protected static function _requireModule($file_path)
    {
        if (is_readable($file_path))
        {
            require $file_path;
            return true;
        }
        return false;
    }

    /**
     * 
     * @param type $dir
     */
    public function registerDir($dir)
    {
        $this->_dirs->set(null, $dir);
    }

    /**
     * 
     * @param array $dirs
     * @return array
     */
    public function registerDirs(array $dirs)
    {
        if (is_callable([$this, rtrim(__FUNCTION__, "s")]))
            return array_map([$this, rtrim(__FUNCTION__, "s")], array_values($dirs));
        return array_map(function($dir) {
            return $this->_dirs->set(null, $dir);
        }, array_values($dirs));
    }

    /**
     * 
     * @param type $name
     * @param type $value
     */
    public function registerExtension($name, $value)
    {
        $this->_extensions->set($name, $value);
    }

    /**
     * 
     * @param array $extensions
     * @return 
     */
    public function registerExtensions(array $extensions)
    {
        $keys = array_keys($extensions);
        $values = array_values($extensions);
        if (is_callable([$this, rtrim(__FUNCTION__, "s")]))
            return array_map([$this, rtrim(__FUNCTION__, "s")], $keys, $values);
        return array_map(function($key, $value) {
            return $this->_extensions->set($key, $value);
        }, $keys, $values);
    }

    /**
     * 
     * @param type $name
     * @return mixed 
     */
    protected function _getExtension($name = null)
    {
        return $this->_extensions->has($name) ? $this->_extensions->get($name) : null;
    }

    /**
     * 
     * @param string $moduleName
     * @param string $extensionType
     * @return boolean
     */
    protected function _loadModule($moduleName, $extensionType)
    {
        return $this->_dirs->some(function ($dir) use($moduleName, $extensionType) {
                    return self::_requireModule(sprintf("%s/%s%s", $dir, $moduleName, $this->_getExtension($extensionType)));
                });
    }

    /**
     * 
     * @param type $function
     * @return boolean
     */
    public function loadFunction($function)
    {
        return is_callable([$this, "_loadModule"]) ? $this->_loadModule($function, "function") :
                $this->_dirs->some(function ($dir) use($function) {
                    return self::_requireModule(sprintf("%s/%s%s", $dir, $function, $this->_getExtension("function")));
                });
    }

    /**
     * 
     * @param type $class
     * @return boolean
     */
    public function loadClass($class)
    {
        return is_callable([$this, "_loadModule"]) ? $this->_loadModule($class, "class") :
                $this->_dirs->some(function ($dir) use($class) {
                    return self::_requireModule(sprintf("%s/%s%s", $dir, $class, $this->_getExtension("class")));
                });
    }

    /**
     * 
     * @param type $abstract_class
     * @return boolean
     */
    public function loadAbstractClass($abstract_class)
    {
        return is_callable([$this, "_loadModule"]) ? $this->_loadModule($abstract_class, "abstract") :
                $this->_dirs->some(function ($dir) use($abstract_class) {
                    return self::_requireModule(sprintf("%s/%s%s", $dir, $abstract_class, $this->_getExtension("abstract")));
                });
    }

    /**
     * 
     * @param type $interface
     * @return boolean
     */
    public function loadInterface($interface)
    {
        return is_callable([$this, "_loadModule"]) ? $this->_loadModule($interface, "interface") :
                $this->_dirs->some(function ($dir) use($interface) {
                    return self::_requireModule(sprintf("%s/%s%s", $dir, $interface, $this->_getExtension("interface")));
                });
    }

    /**
     * 
     * @param type $trait
     * @return boolean
     */
    public function loadTrait($trait)
    {
        return is_callable([$this, "_loadModule"]) ? $this->_loadModule($trait, "trait") :
                $this->_dirs->some(function ($dir) use($trait) {
                    return self::_requireModule(sprintf("%s/%s%s", $dir, $trait, $this->_getExtension("trait")));
                });
    }

    /**
     * 
     * @param type $plugin
     * @return boolean
     */
    public function loadPlugIn($plugin)
    {
        return is_callable([$this, "_loadModule"]) ? $this->_loadModule($plugin, "plugin") :
                $this->_dirs->some(function ($dir) use($plugin) {
                    return self::_requireModule(sprintf("%s/%s%s", $dir, $plugin, $this->_getExtension("plugin")));
                });
    }

}
