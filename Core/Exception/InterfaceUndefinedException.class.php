<?php

/**
 * InterfaceUndefinedException::Exception
 * 
 * 指定されたインターフェースが未定義だった場合、この例外が投げられる
 * This exception is thrown if the specified interface is undefined
 * 
 * @category  FundamentalPHP
 * @package   Core/Exception
 * @copyright
 */
class InterfaceUndefinedException extends Exception {

    protected $_interface, $_errorInfo;

    /**
     * 指定されたインターフェースが未定義だった場合、この例外が投げられる
     * This exception is thrown if the specified interface is undefined
     * 
     * @param string $interfaceName
     * @param string $errorInfo
     */
    public function __construct($interfaceName, $errorInfo = "")
    {
        $this->_interface = (string) $interfaceName;
        $this->_errorInfo = (string) $errorInfo;
        parent::__construct($this->getErrorMessage());
    }

    /**
     * この例外が投げられた際に出力されるエラーメッセージを取得する
     * Get an error message that is output when this exception is thrown
     * 
     * @return string
     */
    public function getErrorMessage()
    {
        return sprintf("Interface '%s' is not defined,'%s'", ucfirst($this->getInterface()), $this->getErrorInfo());
    }

    /**
     * この例外が投げられたインターフェース名を取得する
     * Get the interface name where this exception was thrown
     * 
     * @return string
     */
    public function getInterface()
    {
        return (string) $this->_interface;
    }

    /**
     * この例外が投げられた際の追加情報を取得する
     * Get additional information when this exception is thrown
     * 
     * @return string
     */
    public function getErrorInfo()
    {
        return (string) $this->_errorInfo;
    }

    /**
     * このクラスが文字列として呼び出された場合の処理結果を取得する
     * Get the processing result when this class is called as a string
     * 
     * @link https://www.php.net/manual/ja/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString()
    {
        return join("::", [__CLASS__, $this->getErrorMessage()]);
    }

}
