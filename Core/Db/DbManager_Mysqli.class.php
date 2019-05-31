<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbManager_Mysqli
 */
class DbManager_Mysqli {

    protected $_connection = null;

    /**
     * TODO::コネクションはシングルトン、継承先で常に同じものを使うものとする
     * @param array $params
     * @return \mysqli
     */
    public function connect(array $params)
    {
        $params = array_merge([
            "host" => "",
            "user" => "",
            "password" => "",
            "db" => ""
                ], $params);
        $con = new mysqli($params["host"], $params["user"], $params["password"], $params["db"]);
        if ($con->connect_errno)
            throw new Exception("connect is failed", $con->connect_error);
        $con->query("set names utf8");
        $this->_connection = $con;
    }

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function getConnection()
    {
        return $this->_connection;
    }

}
