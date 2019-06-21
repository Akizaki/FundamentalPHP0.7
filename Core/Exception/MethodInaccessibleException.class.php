<?php

/**
 * MethodInaccessibleException::BadMethodCallException
 * 
 * 指定されたメソッドがアクセス不可能な場合、この例外が投げられる
 * This exception is thrown if the called method can not be accessed
 * 
 * @category  FundamentalPHP
 * @package   Core/Exception
 * @copyright  

 */
class MethodInaccessibleException extends BadMethodCallException {

    protected $_class, $_method, $_errorInfo;

    /**
     * 指定されたメソッドがアクセス不可能な場合、この例外が投げられる
     * This exception is thrown if the called method can not be accessed
     * 
     * @param string $className
     * @param string $methodName
     * @param string $errorInfo
     */
    public function __construct($className, $methodName, $errorInfo = "")
    {
        $this->_class = (string) $className;
        $this->_method = (string) $methodName;
        $this->_errorInfo = (string) $errorInfo;
        parent::__construct($this->getErrorMessage());
    }

    /**
     * Get an error message that is output when this exception is thrown
     * この例外が投げられた際に出力されるエラーメッセージを取得する
     * 
     * @return string
     */
    public function getErrorMessage()
    {
        return sprintf("The called method '%s' is not Inaccessible,'%s'", $this->getInaccessibleMethod(), $this->getErrorInfo());
    }

    /**
     * この例外が投げられたクラス名とメンバ変数名を[クラス::メソッド]のフォーマットで文字列として取得する
     * Get the class name and member variable name 
     * where this exception is thrown as a string in the format of [class :: method]
     * 
     * @return string
     */
    public function getInaccessibleMethod()
    {
        return join("::", [$this->getClass(), $this->getMethod()]);
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
     * この例外が投げられたメソッド名を取得する
     * Get the name of the method for which this exception was thrown
     * 
     * @return string
     */
    public function getMethod()
    {
        return (string) $this->_method;
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
