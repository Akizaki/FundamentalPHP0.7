<?php

/**
 * FileNotExistException::Exception
 * 
 * 指定されたファイルもしくはディレクトリが存在しなかった場合、この例外が投げられる
 * This exception is thrown if the specified file or directory does not exist
 * 
 * @category  FundamentalPHP
 * @package   Core/Exception
 * @copyright  
 */
class FileNotExistException extends Exception {

    protected $_fileName, $_errorInfo;

    /**
     * 指定されたファイルもしくはディレクトリが存在しなかった場合、この例外が投げられる
     * This exception is thrown if the specified file or directory does not exist
     * 
     * @param string $fileName
     * @param string $errorInfo
     */
    public function __construct($fileName, $errorInfo = "")
    {
        $this->_fileName = (string) $fileName;
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
        return sprintf("File or Directory '%s' is not exist,'%s'", $this->getFileName(), $this->getErrorInfo());
    }

    /**
     * この例外が投げられたファイル名もしくはディレクトリ名を取得する
     * Get the file name or directory name where this exception was thrown
     * 
     * @return string
     */
    public function getFileName()
    {
        return (string) $this->_fileName;
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
