<?php

/**
 * Description of Session
 */
class Session {

    protected $_prefix;
    protected static $_isStarted = false;
    protected static $_isRegeneratedId = false;
    protected static $_isCloseEndWrite = false;

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
