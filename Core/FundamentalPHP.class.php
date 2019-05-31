<?php

/**
 * FundamentalPHP
 * 
 * このクラスが継承されたクラスに対しては、そのインスタンス生成後に新たなプロパティをセットする事は出来ない
 * また、存在しないもしくはアクセス出来ないプロパティ、メソッドに対して参照する事も出来ない
 * 
 * マジックメソッド(__get/__set/__call)はアクセス不可(存在しないか未定義)に対して処理が行われた際に実行されるため
 * そこで例外を投げる事でオブジェクトを堅牢に保つ事が出来る
 * 
 * 
 * @category  FundamentalPHP
 * @package   Core
 * @copyright  
 */
abstract class FundamentalPHP {

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
