<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 *
 * @author tatsuya.osada
 */
class Session {

    protected $_prefix;
    protected static $_isStarted = false;
    protected static $_isRegeneratedId = false;
    protected static $_isCloseEndWrite = false;

    /**
     * 
     * @param null $prefix
     */
    public function __construct($prefix = null)
    {
        $this->_prefix = is_null($prefix) ? "__default" : $prefix;
        self::start($this->_prefix);
        if (!array_key_exists($this->_prefix, $_SESSION))
            $_SESSION[$this->_prefix] = [];
    }

    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function getItem($name)
    {
        return array_key_exists($name, $_SESSION[$this->_prefix]) ? $_SESSION[$this->_prefix][$name] : null;
    }

    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getItem($name);
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    public function setItem($name, $value)
    {
        $_SESSION[$this->_prefix][$name] = $value;
        if (self::$_isCloseEndWrite)
            self::closeWrite();
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->setItem($name, $value);
    }

    /**
     * 
     * @param string $name
     * @return
     */
    public function removeItem($name)
    {
        if (!array_key_exists($name, $_SESSION[$this->_prefix]))
            return;
        unset($_SESSION[$this->_prefix][$name]);
    }

    /**
     * 
     * @param boolean $destory
     */
    public static function regenerate($destory = true)
    {
        if (!self::$_isRegeneratedId)
        {
            session_regenerate_id($destory);
            self::$_isRegeneratedId = true;
        }
    }

    /**
     * 
     */
    public function clear()
    {
        unset($_SESSION[$this->_prefix]);
    }

    /**
     * 
     */
    public function destory()
    {
        unset($_SESSION[$this->_prefix]);
        if (!count($_SESSION) && self::$_isStarted)
            session_destroy();
    }

    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function exists($name)
    {
        return array_key_exists($name, $_SESSION[$this->_prefix]);
    }

    /**
     * 
     * @param null $prefix
     */
    public static function start($prefix = null)
    {
        if (!self::$_isStarted)
        {
            session_name(is_null($prefix) ? "__default" : $prefix);
            session_start();
            self::$_isStarted = true;
        }
    }

    /**
     * for csrf
     * @return string
     */
    public function createTicket()
    {
        $ticket = sha1(uniqid(rand()));
        $this->setItem('__ticket', $ticket);
        return (string) $ticket;
    }

    /**
     * 
     * @param string $ticket
     * @return boolean
     */
    public function checkTicket($ticket)
    {
        return (string) $this->getItem('__ticket') === (string) $ticket;
    }

    /**
     * 
     */
    public function deleteTicket()
    {
        $this->removeItem('__ticket');
    }

    /**
     * 
     * @param boolean $isRegenerated
     */
    public static function setIsRegeneratedId($isRegenerated)
    {
        self::$_isRegeneratedId = (bool) $isRegenerated;
    }

    /**
     * 
     * @param boolean $isClose
     */
    public static function isCloseEndWrite($isClose)
    {
        self::setIsCloseEndWrite($isClose);
    }

    /**
     * 
     * @param boolean $isClose
     */
    public static function setIsCloseEndWrite($isClose)
    {
        self::$_isCloseEndWrite = $isClose;
    }

    /**
     * 
     */
    public static function closeWrite()
    {
        session_write_close();
        self::$_isStarted = false;
    }

}
