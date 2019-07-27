<?php

/**
 * FundamentalPHP
 * 
 * <pre>このクラスが継承されたクラスに対しては、そのインスタンス生成後に新たなプロパティをセットする事は出来ない
 * また、存在しないもしくはアクセス出来ないプロパティ、メソッドに対して参照する事も出来ない</pre>
 * 
 * <pre>マジックメソッド(__get/__set/__call)はアクセス不可(存在しないか未定義)に対して処理が行われた際に実行されるため
 * そこで例外を投げる事でオブジェクトを堅牢に保つ事が出来る</pre>
 * 
 * 
 * @category  FundamentalPHP
 * @package   Core
 * @copyright
 */
abstract class FundamentalPHP {

    /**
     * アクセスできない、または区別されていないメソッドを呼び出そうとした例外をスローする
     * Throw exception that was going to call method which cannot be accessed or not difined 
     * 
     * @param string $name
     * @param array $arguments
     * @throws MethodInaccessibleException
     * @throws MethodUndefinedException
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, get_class_methods($this)))
            throw new MethodInaccessibleException(get_class($this), $name);
        else
            throw new MethodUndefinedException(get_class($this), $name);
    }

    /**
     * アクセスできない、または区別されていないフィールドに値を取得しようとしていた例外をスローする
     * Throw exception that was going to get value to field which cannot be accessed or not difined 
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
     * アクセスできない、または区別されていないフィールドに値を取得しようとしていた例外をスローする
     * Throw exception that was going to get value to field which cannot be accessed or not difined 
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
