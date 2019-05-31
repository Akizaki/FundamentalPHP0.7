<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Logger
 * 
 */
class Logger {

    protected $_logFile;
    protected $_dateTime;

    /**
     * 
     * @param type $logfile
     */
    public function __construct($logfile = null)
    {
        //TODO::file_exist($logfile)::引数のログファイルが存在する場合
        $this->_logFile = is_null($logfile) ? "error.log" : $logfile;
        $this->_dateTime = new DateTime();
        $this->_dateTime->setTimezone(new DateTimeZone("Asia/Tokyo"));
    }

    /**
     * TODO::write???
     * @param type $message
     * @param type $datetime
     */
    public function write($message)
    {
        if (!is_readable($this->_logFile))
            throw new Exception("logFile is not exist");
        $logFile = fopen($this->_logFile, "a");
        if (fwrite($logFile, $this->_dateTime->format("Y-m-d H:i:s") . "::" . $message . PHP_EOL))
            return true;
        return false;
    }

    /**
     * 
     * @param type $fileName
     * @return boolean
     */
    public function create($fileName)
    {
        if (file_exists($fileName))
            return false;
        return touch($fileName);
    }

    /**
     * 
     * @return mixed
     */
    public function getLogFile()
    {
        return file_get_contents($this->_logFile);
    }

    /**
     * 
     * @return type
     */
    public function showLogtoHtml()
    {
        return array_walk(file($this->_logFile), function ($log_text) {
            echo "<pre>" . $log_text . "</pre>";
        });
    }

}
