<?php

/**
 * Description of ApplicationController
 *
 */
class ApplicationController extends Controller {

    /**
     * 
     * @param type $params
     */
    public function mainAction($params = null)
    {
        $date = new DateTime(implode("-", $params));
        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {

            if (is_uploaded_file($_FILES["image"]["tmp_name"]))
            {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], "./web/" . $_FILES["image"]["name"]))
                {
                    exit("uploaded");
                }
                exit("error");
            }
        }
    }
    
    /**
     * 
     */
    public function errorAction()
    {
        $this->_logger->showLogtoHtml();
    }

    /**
     * 
     */
    public function postAction($params = null)
    {
        echo "postAction is called";
    }

    /**
     * 
     * @return 
     */
    public function getDate()
    {
        $path_info = explode("/", $this->_request->getPathInfo());
        $date_time = ["Year" => $path_info[3], "Month" => $path_info[4], "Day" => $path_info[5]];
        return $date_time;
    }

    /**
     * 
     * @return type
     */
    public function getRootDir()
    {
        return dirname(__FILE__);
    }

    public function configure()
    {
        
    }

}
