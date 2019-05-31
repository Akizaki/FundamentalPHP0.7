<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Db_articles
 *
 * 
 */
class Db_articles extends DbManager_Mysqli {

    /**
     * 
     * @return array
     */
    public function getResource()
    {
        $result = [];
        $stmt = $this->_connection->prepare("SELECT title,datetime,text,author FROM article");
        $stmt->execute();
        $place_holder = $stmt->get_result();
        while ($row = $place_holder->fetch_array(MYSQLI_ASSOC))
            array_push($result, $row);
        return $result;
    }

}
