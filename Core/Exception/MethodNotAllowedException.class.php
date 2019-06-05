<?php

/**
 * MethodNotAllowedException::BadMethodCallException
 * 
 * 禁止されているHTTPメソッドが呼び出された際、この例外が投げられる
 * This exception is thrown when a forbidden HTTP method is called
 * 
 * @todo この例外クラスではメンバ「_forbiddenMethod」がHTTPメソッドかそうでないかは判断していない
 * 
 * @category  FundamentalPHP
 * @package   Core/Exception
 * @copyright 
 */
class MethodNotAllowedException extends BadMethodCallException {

    protected $_forbiddenMethod, $_errorInfo;

    /**
     * 禁止されているHTTPメソッドが呼び出された際、この例外が投げられる
     * This exception is thrown when a forbidden HTTP method is called
     * 
     * @param string $forbiddenMethodName
     * @param string $errorInfo
     */
    public function __construct($forbiddenMethodName, $errorInfo = "")
    {
        $this->_forbiddenMethod = (string) $forbiddenMethodName;
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
        return sprintf("The called method '%s' is forbidden to use,'%s'", strtoupper($this->getForbiddenMethod()), $this->getErrorInfo());
    }

    /**
     * この例外が投げられた禁止されているメソッド名を取得する
     * Get the forbidden method name for which this exception was thrown
     * 
     * @return string
     */
    public function getForbiddenMethod()
    {
        return (string) $this->_forbiddenMethod;
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
