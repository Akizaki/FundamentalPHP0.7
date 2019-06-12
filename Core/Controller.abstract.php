<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 * 
 */
abstract class Controller {

    protected
            $_request,
            $_response,
            $_session,
            $_dbManager,
            $_logger,
            $_controllerName,
            $_actionName,
            $_view,
            $_debugMode;

    /**
     * 
     * @param type $debug_mode
     * @param type $mysql_driver
     */
    public function __construct($debug_mode = false, $mysql_driver = "Mysqli")
    {
        $this->_setDebugMode($debug_mode);
        $this->_controllerName = strtolower(substr(get_class($this), 0, -10));
        $this->_logger = new Logger();
        $this->_request = new Request();
        $this->_response = new Response();
        $this->_session = new Session(); //TODO::単体テスト
        $this->_dbManager = $this->_setDataBaseDriver($mysql_driver);
        $this->_view = new View($this->_response);
        $this->_actionName = $this->_findMethod();
    }

    /**
     * 
     * @param type $driver_name
     * @return \DbManager_Mysql|\DbManager_Mysqli|\DbManager_PDO
     */
    protected function _setDataBaseDriver($driver_name = null)
    {
        if (is_null($driver_name))
            throw new Exception("Please specify the Driver for the Database");
        return $driver_name === "PDO" ? new DbManager_PDO() :
                $driver_name === "Mysqli" ? new DbManager_Mysqli() :
                $driver_name === "Mysql" ? new DbManager_Mysql() : false;
    }

    /**
     * 
     * @param type $debug
     */
    protected function _setDebugMode($debug = false)
    {
        if ($debug)
        {
            $this->debug = true;
            ini_set('display_errors', 1);
            error_reporting(-1);
        }
        else
        {
            $this->debug = false;
            ini_set('display_errors', 0);
        }
    }

    /**
     * 
     * @return type
     * @throws NotFoundException
     */
    public function dispatch()
    {
        $this->_actionName = explode("/", $this->_request->getPathInfo())[2];
        if (!is_callable([$this, $this->_actionName]))
            throw new NotFoundException($this->_controllerName, $this->_actionName);
        $datetime = $this->getDate();
        $action = $this->_actionName;
        return $this->$action($datetime);
    }

    /**
     * 
     * @return string
     */
    protected function _findController()
    {
        foreach (explode("/", $this->_request->getPathInfo()) as $value)
            if ($value === $this->_controllerName)
                return $value;
        return false;
    }

    /**
     * @todo ここでアクションが見つからなかった場合にfalseを返すから例外のときにアクション名が見つからない
     * @return mixed 
     */
    protected function _findMethod()
    {
        $methods = array_values(array_filter(get_class_methods($this), function ($action) {
                    return strpos($action, "Action") !== false;
                }));
        $path_info = explode("/", $this->_request->getPathInfo());
        foreach ($path_info as $url)
            foreach ($methods as $method)
                if ($url === substr($method, 0, -6))
                    return $method;
        return false;
    }

    /**
     * 
     */
    abstract public function configure();

    /**
     * 
     */
    abstract public function getRootDir();

    /**
     * 
     * @return Request
     */
    public function getRequest()
    {
        return $this->_request instanceof Request ? $this->_request : new Request();
    }

    /**
     * 
     * @return Response
     */
    public function getResponse()
    {
        return $this->_response instanceof Response ? $this->_response : new Response();
    }

    /**
     * 
     * @return View
     */
    public function getView()
    {
        return $this->_view instanceof View ? $this->_view : new View();
    }

    /**
     * 
     * @return Session
     */
    public function getSession($prefix = null)
    {
        return $this->_session instanceof Session ? $this->_session : new Session($prefix);
    }

    /**
     * 
     * @return string
     */
    public function getControllerDir()
    {
        return $this->getRootDir() . "/Controller";
    }

    /**
     * 
     * @return string
     */
    public function getFunctionDir()
    {
        return (string) $this->getRootDir() . "/Function";
    }

    /**
     * 
     * @return string
     */
    public function getInterfaceDir()
    {
        return $this->getRootDir() . "/Interface";
    }

    /**
     * 
     * @return string
     */
    public function getDbDir()
    {
        return $this->getRootDir() . "/Db";
    }

}
