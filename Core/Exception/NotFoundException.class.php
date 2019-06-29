<?php

/**
 * NotFoundException::RuntimeException
 * 
 * URIで指定されたpathInfoが見つからなかった場合、この例外が投げられる
 * This exception is thrown if the pathInfo specified by URI is not found
 * 
 * @link https://www.php.net/manual/ja/class.runtimeexception.php
 * @category  FundamentalPHP
 * @package   Core/Exception
 * @copyright  
 */
class NotFoundException extends RuntimeException {

    protected $_controller, $_action, $_errorInfo;

    /**
     * URIで指定されたpathInfoが見つからなかった場合、この例外が投げられる
     * This exception is thrown if the pathInfo specified by URI is not found
     * 
     * @param string $controllerName
     * @param string $actionName
     * @param string $errorInfo
     */
    public function __construct($controllerName, $actionName, $errorInfo = "")
    {
        $this->_controller = (string) $controllerName;
        $this->_action = (string) $actionName;
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
        return sprintf("The Path '%s' is not found,'%s'", $this->getMissingPathInfo(), $this->getErrorInfo());
    }

    /**
     * この例外が投げられたPathInfoを[コントローラー名/アクション名]のフォーマットで取得する
     * Get PathInfo where this exception is thrown in the format of [controller / action]
     * 
     * @return string
     */
    public function getMissingPathInfo()
    {
        return join("/", [$this->getController(), $this->getAction()]);
    }

    /**
     * この例外が投げられたコントローラー名を取得する
     * Get the controller name where this exception was thrown
     * 
     * @return string
     */
    public function getController()
    {
        return (string) $this->_controller;
    }

    /**
     * この例外が投げられたアクション名を取得する
     * Get the name of the action this exception was thrown
     * 
     * @return string
     */
    public function getAction()
    {
        return (string) $this->_action;
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
