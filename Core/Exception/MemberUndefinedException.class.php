<?php

/**
 * MemberUndefinedException::Exception
 * 
 * 指定されたメンバ変数が定義されていなかった場合、この例外が投げられる
 * This exception is thrown if the specified member variable is not defined
 * 
 * @category  FundamentalPHP
 * @package   Core/Exception
 * @copyright  
 */
class MemberUndefinedException extends Exception {

    protected $_class, $_member, $_errorInfo;

    /**
     * 指定されたメンバ変数が定義されていなかった場合、この例外が投げられる
     * This exception is thrown if the specified member variable is not defined
     * 
     * @param string $className
     * @param string $memberName
     * @param string $errorInfo
     */
    public function __construct($className, $memberName, $errorInfo = "")
    {
        $this->_class = (string) $className;
        $this->_member = (string) $memberName;
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
        return sprintf("'%s' is not defined,'%s'", $this->getUndefinedMember(), $this->getErrorInfo());
    }

    /**
     * この例外が投げられたクラス名とメンバ変数名を[クラス::メンバ]のフォーマットで文字列として取得する
     * Get the class name and member variable name 
     * where this exception is thrown as a string in the format of [Class::member]
     * 
     * @return string
     */
    public function getUndefinedMember()
    {
        return join("::", [ucfirst($this->getClass()), $this->getMember()]);
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
     * この例外が投げられたメンバ変数名を取得する
     * Get the member variable name where this exception was thrown
     * 
     * @return string
     */
    public function getMember()
    {
        return (string) $this->_member;
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
