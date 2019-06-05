<?php

/**
 * Request::FundamentalPHP
 * 
 * Control HTTP/HTTPS request
 * 
 * @category FundamentalPHP
 * @package  Core
 * @copyright
 */
class Request extends FundamentalPHP {

    protected $_forbiddenMethods, $_http_methods, $_filterTypeList;

    /**
     * 
     * 
     * @param array $forbiddenMethods
     */
    public function __construct($forbiddenMethods = null)
    {
        $this->_http_methods = ["GET", "POST", "PUT", "DELETE", "HEAD", "OPTIONS", "PATCH"];
        $this->_forbiddenMethods = is_array($forbiddenMethods) ? array_map("strtoupper", $forbiddenMethods) : [];
        $this->_filterTypeList = function_exists("filter_list") ? array_map("strtoupper", filter_list()) : [];
    }

    /**
     * 
     * @return boolean
     */
    public function isPost()
    {
        return strtoupper($_SERVER["REQUEST_METHOD"]) === "POST";
    }

    /**
     * 
     * @return boolean
     */
    public function isSsl()
    {
        return isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on";
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
     * @todo _forbiddenMethodsに対してHTTTPメソッドであるかどうか判定する必要がある
     */
    public function isHttpRequestMethod()
    {
        return;
    }

    /**
     * 
     * @param string $request_method
     * @return boolean
     * @throws MethodNotAllowedException
     */
    public function is($request_method)
    {
        if (in_array(strtoupper($request_method), $this->getForbiddenMethods()))
            throw new MethodNotAllowedException(strtoupper($request_method));
        if ($request_method === "ssl")
            return $this->isSsl();
        if ($request_method === "ajax" || $request_method === "xmlhttprequest")
            return $this->isXmlHttpRequest();
        return strtoupper($_SERVER["REQUEST_METHOD"]) === strtoupper($request_method);
    }

    /**
     * 
     * @param string $name
     * @param mixed $__FILTER 
     * <pre>See below for available filter types</pre>
     * @link https://www.php.net/manual/ja/filter.filters.php
     * @return mixed
     */
    public function getPost($name, $__FILTER = null)
    {
        $__FILTER = in_array(strtoupper($__FILTER), $this->getFilterTypeList()) ?
                strtoupper($__FILTER) : FILTER_UNSAFE_RAW;
        if (isset($_POST[$name]))
            return function_exists("filter_input") ? filter_input(INPUT_POST, $name, $__FILTER) : $_POST[$name];
        return null;
    }

    /**
     * 
     * @param string $name
     * @param mixed $__FILTER 
     * <pre>See below for available filter types</pre>
     * @link https://www.php.net/manual/ja/filter.filters.php
     * @return mixed
     */
    public function getQuery($name, $__FILTER = null)
    {
        $__FILTER = in_array(strtoupper($__FILTER), $this->getFilterTypeList()) ?
                strtoupper($__FILTER) : FILTER_UNSAFE_RAW;
        if (isset($_GET[$name]))
            return function_exists("filter_input") ? filter_input(INPUT_GET, $name, $__FILTER) : $_GET[$name];
        return null;
    }

    /**
     * 
     * @return array
     */
    public function getForbiddenMethods()
    {
        return (array) $this->_forbiddenMethods;
    }

    /**
     * 
     * @return array
     */
    public function getFilterTypeList()
    {
        return (array) $this->_filterTypeList;
    }

    /**
     * 
     * @return array
     */
    public function getHeaders()
    {
        return function_exists("apache_request_headers") ? apache_request_headers() : [];
    }

    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function getHeader($name)
    {
        if (function_exists("apache_request_headers"))
            return array_key_exists($name, apache_request_headers()) ? apache_request_headers()[$name] : "";
        return null /*  TODO::false? */;
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
