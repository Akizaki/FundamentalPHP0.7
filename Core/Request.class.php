<?php

/**
 * Request::FundamentalPHP
 * 
 * Control HTTP/HTTPS request
 * 
 * @category FundamentalPHP
 * @package  Core
 * @copyright
 * @link https://github.com/yumezouWebTeam/FundamentalPHP0.8.1
 * @link https://github.com/yumezouWebTeam/FundamentalPHP0.8.1/blob/master/Core/Request.class.php
 */
class Request extends FundamentalPHP {

    protected $_forbiddenMethods, $_http_methods, $_filterTypeList;

    /**
     * 
     * @param null/array $forbiddenMethods
     */
    public function __construct($forbiddenMethods = null)
    {
        /* TODO::要テスト $this->_http_methods = ["GET", "POST", "PUT", "DELETE", "HEAD", "OPTIONS", "PATCH"]; */
        $this->_forbiddenMethods = is_array($forbiddenMethods) ? array_map("strtoupper", $forbiddenMethods) : [];
        $this->_filterTypeList = function_exists("filter_list") ? array_map("strtoupper", filter_list()) : [];
    }

    /**
     * 
     * @return boolean
     */
    public function isXmlHttpRequest()
    {
        return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
                strtoupper($_SERVER["HTTP_X_REQUESTED_WITH"]) === strtoupper("xmlhttprequest");
    }

    /**
     * 
     * @return string
     */
    public function getRequestUri()
    {
        return function_exists("filter_input") ?
                filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_UNSAFE_RAW) : $_SERVER["REQUEST_URI"];
    }

    /**
     * 
     * @return string
     */
    public function getBaseUrl()
    {
        $request_uri = $this->getRequestUri();
        $script_name = filter_input(INPUT_SERVER, "SCRIPT_NAME", FILTER_DEFAULT);
        if (strpos($request_uri, $script_name) === 0)
            return $script_name;
        else if (strpos($request_uri, dirname($script_name)) === 0)
            return rtrim(dirname($script_name), "/");
        return "";
    }

    /**
     * 
     * @return string
     */
    public function getPathInfo()
    {
        $request_uri = $this->getRequestUri();
        $base_url = $this->getBaseUrl();
        if (($pos = strpos($request_uri, "?")) !== false)
            $request_uri = substr($request_uri, 0, $pos);
        $path_info = (string) substr($request_uri, strlen($base_url));
        return $path_info;
    }

    /**
     * 
     * @return string
     */
    public function getController()
    {
        return explode("/", $this->getPathInfo())[1];
    }

    /**
     * 
     * @return string
     */
    public function getAction()
    {
        return explode("/", $this->getPathInfo())[2];
    }

    /**
     * 
     * @return array
     */
    public function getQueryParams()
    {
        $array = [];
        foreach (explode("/", $this->getPathInfo()) as $value)
            if ((int) $value)
                $array[] = $value;
        return $array;
    }

}
