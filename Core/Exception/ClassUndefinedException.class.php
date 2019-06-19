<?php

/**
 * ClassUndefinedException::Exception
 * 
 * 指定されたクラスが未定義だった場合、この例外が投げられる
 * This exception is thrown if the specified class is undefined
 * 
 * @link 
 * @category  FundamentalPHP
 * @package   Core/Exception
 * @copyright
 */
class ClassUndefinedException extends Exception {

    protected $_class, $_errorInfo;

    /**
     * 指定されたクラスが未定義だった場合、この例外が投げられる
     * This exception is thrown if the specified class is undefined
     * 
     * @param string $className
     * @param string $errorInfo
     */
    public function __construct($className, $errorInfo = "")
    {
        $this->_class = (string) $className;
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
        return sprintf("Class '%s' is not defined,'%s'", ucfirst($this->getClass()), $this->getErrorInfo());
    }

    /**
     * この例外が投げられたクラス名を取得する
     * Get the class name where this exception was thrown
     * 
     * @return string
     */
    public function getClass()
    {
        return (string) $this->_class;
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
        return sprintf("Exception '%s' with message '%s' in '%s'", __CLASS__, $this->getErrorMessage(), join(":", [$this->getFile(), $this->getLine()]));
    }

}
