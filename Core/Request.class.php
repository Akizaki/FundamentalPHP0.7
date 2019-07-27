<?php

/**
 * Request::FundamentalPHP
 * 
 * Control HTTP/HTTPS request
 * 
 * @category FundamentalPHP
 * @package  Core
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

        /**
         * _forbiddenMethodsに指定されたパラメータ群のうち、HTTPメソッドでないものを省く
         */
        $this->_forbiddenMethods = array_filter($forbiddenMethods, function ($forbiddenMethod) {
            return array_filter($this->_http_methods, function ($http_method) use($forbiddenMethod) {
                return $http_method === $forbiddenMethod;
            });
        });

        /**
         * TODO::リクエストメソッドの値を受け付ける際の利用可能なフィルタリストを生成する
         */
        $this->_filterTypeList = array_merge([
            FILTER_SANITIZE_EMAIL,
            FILTER_SANITIZE_ENCODED,
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            FILTER_SANITIZE_URL,
            FILTER_VALIDATE_BOOLEAN,
            FILTER_VALIDATE_EMAIL,
            FILTER_VALIDATE_IP,
                ], array_map("strtoupper", filter_list()));
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
