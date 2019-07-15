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
 * @author tatsuya.osada
 */
class Request extends FundamentalPHP {

    protected $_forbiddenMethods, $_http_methods, $_filterTypeList;

    /**
     * コンストラクタ、初期パラメータの設定
     * Constructor setting of initial parameters
     * 
     * @param array $forbiddenMethods
     */
    public function __construct(array $forbiddenMethods = [])
    {
        $this->_http_methods = ["GET", "POST", "PUT", "DELETE", "HEAD", "OPTIONS", "PATCH"];
        $this->_forbiddenMethods = array_filter($forbiddenMethods, function ($forbiddenMethod) {
            return array_filter($this->_http_methods, function ($http_method) use($forbiddenMethod) {
                return $http_method === $forbiddenMethod;
            });
        });

        /**
         * 
         */
        $this->_filterTypeList = array_merge([
            FILTER_SANITIZE_EMAIL, FILTER_SANITIZE_ENCODED, FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_URL, FILTER_VALIDATE_BOOLEAN,
            FILTER_VALIDATE_EMAIL, FILTER_VALIDATE_IP,
                ], array_map("strtoupper", filter_list()));
    }

    /**
     * ページにアクセスする際に使用されたHTTPリクエストメソッドを総合的に判定する
     * Comprehensively check the HTTP request method used to access the page
     * 
     * @param string $request_method
     * @return boolean
     * @throws MethodNotAllowedException
     */
    public function is($request_method)
    {
        if (in_array(strtoupper($request_method), $this->_forbiddenMethods))
            throw new MethodNotAllowedException(strtoupper($request_method));
        if ($request_method === "ssl")
            return $this->isSsl();
        if ($request_method === "ajax" || $request_method === "xmlhttprequest")
            return $this->isXmlHttpRequest();
        return strtoupper($_SERVER["REQUEST_METHOD"]) === strtoupper($request_method);
    }

    /**
     * ページにアクセスする際に使用されたリクエストのメソッドがPOSTであるか判定する
     * Check if the method of the request used to access the page is POST
     * 
     * @return boolean
     */
    public function isPost()
    {
        return strtoupper($_SERVER["REQUEST_METHOD"]) === "POST";
    }

    /**
     * リクエストがHTTPSプロトコルを通して実行されているか判定する
     * Check if the request is being executed through the HTTPS protocol
     * 
     * @return boolean
     */
    public function isSsl()
    {
        return isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on";
    }

    /**
     * リクエストがXmlHttpRequestかどうか判定する
     * Check if the request is XmlHttpRequest
     * 
     * @return boolean
     */
    public function isXmlHttpRequest()
    {
        return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
                strtoupper($_SERVER["HTTP_X_REQUESTED_WITH"]) === strtoupper("xmlhttprequest");
    }

    /**
     * 禁止されたHTTPリクエストメソッドの一覧を配列で取得する
     * Get a list of forbidden HTTP request methods as an array
     * 
     * @return array
     */
    public function getForbiddenMethods()
    {
        return (array) $this->_forbiddenMethods;
    }

    /**
     * 禁止するHTTPリクエストメソッドをプロパティ設定する
     * Set to Forbidden HTTP request method
     * 
     * @param type $method_name
     * @return 
     */
    public function setForbiddenMethod($method_name)
    {
        $this->_forbiddenMethods[] = (string) strtoupper($method_name);
    }

    /**
     * 禁止するHTTPリクエストメソッドを配列でまとめて設定する
     * Set collectively Forbidden HTTP request methods in an array
     * 
     * @param array $methods
     * @return array
     */
    public function setForbiddenMethods(array $methods)
    {
        return array_map(function($method) {
            return is_string($method) && $this->_forbiddenMethods[] = strtoupper($method);
        }, $methods);
    }

    /**
     * 
     * @param string $http_method
     */
    public function setHttpMethod($http_method)
    {
        /* TODO::Not Implemented  */
    }

    /**
     * 
     * @param array $http_method
     */
    public function setHttpMethods(array $http_method)
    {
        /* TODO::Not Implemented  */
    }

    /**
     * 利用可能なフィルタの型を配列で取得する
     * Get available filter types in array
     * 
     * @return array
     */
    public function getFilterTypeList()
    {
        return (array) $this->_filterTypeList;
    }

    /**
     * 
     * @param string $filter_type
     */
    public function setFilterType($filter_type)
    {
        /* TODO::Not Implemented  */
    }

    /**
     * 
     * @param array $filter_types
     */
    public function setFilterTypes(array $filter_types)
    {
        /* TODO::Not Implemented  */
    }

    /**
     * リクエストメソッドPOSTのパラメータをフィルタリングして取得する
     * Filter and get the parameters of request method POST
     * 
     * @param string $name
     * @param string $__FILTER__
     * <pre>See below for available filter types</pre>
     * @link https://www.php.net/manual/ja/filter.filters.php
     * @return mixed
     */
    public function getPost($name, $__FILTER__ = FILTER_DEFAULT)
    {
        $__FILTER__ = in_array(strtoupper($__FILTER__), $this->getFilterTypeList()) ? strtoupper($__FILTER__) : FILTER_DEFAULT;
        if (isset($_POST[$name]))
            return function_exists("filter_input") ? filter_input(INPUT_POST, $name, $__FILTER__) : $_POST[$name];
        return null;
    }

    /**
     * リクエストメソッドGETのクエリパラメータをフィルタリングして取得する
     * Filter and get the query parameter of request method GET
     * 
     * @param string $name
     * @param string || null $__FILTER__
     * <pre>See below for available filter types</pre>
     * @link https://www.php.net/manual/ja/filter.filters.php
     * @return mixed
     */
    public function getQuery($name, $__FILTER__ = FILTER_DEFAULT)
    {
        $__FILTER__ = in_array(strtoupper($__FILTER__), $this->getFilterTypeList()) ? strtoupper($__FILTER__) : FILTER_DEFAULT;
        if (isset($_GET[$name]))
            return function_exists("filter_input") ? filter_input(INPUT_GET, $name, $__FILTER__) : $_GET[$name];
        return null;
    }

    /**
     * 指定されたリクエストヘッダを取得する
     * Get specified request header
     * 
     * @param nullable $name
     * @return mixed
     */
    public function getHeader($name = null)
    {
        if (function_exists("apache_request_headers"))
            return array_key_exists($name, apache_request_headers()) ? apache_request_headers()[$name] : null;
        return null;
    }

    /**
     * リクエストヘッダを配列で取得する
     * Get request header in array
     * 
     * @return array
     */
    public function getHeaders()
    {
        return function_exists("apache_request_headers") ? apache_request_headers() : [];
    }
    

    /**
     * ページにアクセスするために指定されたURIを取得する
     * Get the specified URI to access the page
     * 
     * @return string
     */
    public function getRequestUri()
    {
        return function_exists("filter_input") ?
                filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_UNSAFE_RAW) : $_SERVER["REQUEST_URI"];
    }

    /**
     * ページにアクセスするために指定されたURIのベースURL(PATHINFOを除いたもの)を取得する
     * Get base URL of URI specified for accessing page (except for PATHINFO)
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
     * ページにアクセスするために指定されたURIのPATHINFOを取得する
     * Get PATHINFO of the specified URI to access the page
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
     * Get the controller part of PATHINFO specified to access the page
     * ページにアクセスするために指定されたPATHINFOのコントローラー部分を取得する
     * 
     * @return string
     */
    public function getController()
    {
        return explode("/", $this->getPathInfo())[1];
    }

    /**
     * ページにアクセスするために指定されたPATHINFOのアクション部分を取得する
     * Get the action part of PATHINFO specified to access the page
     * 
     * @return string
     */
    public function getAction()
    {
        return explode("/", $this->getPathInfo())[2];
    }

    /**
     * ページにアクセスするために指定されたPATHINFOのクエリパラメータを配列で取得する
     * Get query parameter of PATHINFO specified to access page as array
     * 
     * @return array
     */
    public function getQueryParams()
    {
        $array = [];
        foreach (explode("/", $this->getPathInfo()) as $value)
            if ((int) $value)
                $array[] = $value;
        return (array) $array;
    }

}
