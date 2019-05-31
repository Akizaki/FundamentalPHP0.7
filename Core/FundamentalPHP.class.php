<?php

/**
 * FundamentalPHP
 * 
 * @tutorial 
 * このクラスが継承されたクラスに対しては、そのインスタンス生成後に新たなプロパティをセットする事は出来ない
 * また、存在しないもしくはアクセス出来ないプロパティ、メソッドに対して参照する事も出来ない
 * マジックメソッド(__get/__set/__call)はアクセス不可(存在しないか未定義)に対して処理が行われた際に実行されるため
 * そこで例外を投げる事でオブジェクトを堅牢に保つ事が出来る
 * 
 * @category  FundamentalPHP
 * @package   Core
 * @copyright  
 */
abstract class FundamentalPHP {

    public function __call($name, $arguments)
    {
        
    }

    /**
     * アクセス出来ない、または未定義のメンバ変数に値をセットしようとした際に例外を投げる
     * Throw an exception when trying to set a value to an inaccessible or undefined member variable
     * 
     * @param string $name
     * @param mixed $value
     * @throws MemberInaccessibleException
     * @throws MemberUndefinedException
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, get_object_vars($this)))
            throw new MemberInaccessibleException(get_class($this), $name);
        else
            throw new MemberUndefinedException(get_class($this), $name);
    }

    /**
     * アクセス出来ない、または定義されていないメンバ変数を参照しようとした際に例外を投げる
     * Throw an exception when trying to reference a member variable that can not be accessed or defined
     * 
     * @param string $name
     * @throws MemberInaccessibleException
     * @throws MemberUndefinedException
     */
    public function __get($name)
    {
        if (array_key_exists($name, get_object_vars($this)))
            throw new MemberInaccessibleException(get_class($this), $name);
        else
            throw new MemberUndefinedException(get_class($this), $name);
    }

    /**
     * Check whether argument type is array or ArrayObject instance
     * 
     * @param string $methodName
     * @param int $argumentNo
     * @param mixed $argument
     * @return boolean
     * @throws ArgumentInvalidException
     */
    protected function _isArgumentIsArray($methodName, $argumentNo, $argument)
    {
        if (!is_array($argument) && !($argument instanceof ArrayObject))
            throw new ArgumentInvalidException($methodName, $argument, $argumentNo, 'array or ArrayObject');
        return true;
    }

}
