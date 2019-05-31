<?php

/**
 * 
 */
class DbManager_PDO extends DbManager {

    /**
     *
     * @var array
     */
    protected $_connections = [];

    /**
     * 
     * @param type $name
     * @param array $params
     */
    public function connect($name, array $params)
    {
        $params = array_merge([
            "dsn" => null,
            "user" => "",
            "password" => "",
            "options" => []
                ], $params);

        $con = new PDO(
                $params["dsn"], $params["user"], $params["password"], $params["options"]
        );

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_connections[$name] = $con;
    }

    /**
     * 
     * @param type $name
     * @return PDO
     */
    public function getConnection($name = null)
    {
        if (is_null($name))
            return current($this->_connections);
        return $this->_connections[$name];
    }

}

?>