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
        $this->_http_methods = ["GET", "POST", "PUT", "DELETE", "HEAD", "OPTIONS", "PATCH", "CONNECT", "TRACE"];

        /**
         * _forbiddenMethodsに指定されたパラメータ群のうち、HTTPメソッドでないものを省く
         * 
         */
        $this->_forbiddenMethods = array_filter($forbiddenMethods, function ($forbiddenMethod) {
            return array_filter($this->_http_methods, function ($http_method) use($forbiddenMethod) {
                return $http_method === strtoupper($forbiddenMethod);
            });
        });


        /**
         * リクエストメソッドの値を受け付ける際の利用可能なフィルタリストのIDを生成する
         */
        $this->_filterTypeList = array_merge([
            FILTER_SANITIZE_ENCODED,
            FILTER_SANITIZE_SPECIAL_CHARS,
            FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            FILTER_SANITIZE_URL,
            FILTER_VALIDATE_BOOLEAN,
            FILTER_VALIDATE_EMAIL,
            FILTER_VALIDATE_IP,
                ], array_map("filter_id", filter_list()));
    }

    /**
     * ページにアクセスする際に使用されたリクエストのメソッドがPOSTであるか判定する
     * Check if the method of the request used to access the page is POST
     * 
     * @return boolean
     */
    public function isPost()
    {
        return strtoupper($_SERVER["REQUEST_METHOD"]) === strtoupper("post");
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
     * ページにアクセスする際に使用されたリクエストのメソッドを判定する
     * Check the request method used to access the page
     * 
     * @param string $request_method
     * @return boolean
     * @throws MethodNotAllowedException
     */
    public function is($request_method)
    {
        if (!$this->_checkAllowedMethod($request_method))
        {
            function_exists("http_response_code") ?
                            http_response_code(405) : header("HTTP/1.1 405 Method Not Allowed");
            throw new MethodNotAllowedException(strtoupper($request_method));
        }
        if ($request_method === "ssl")
            return $this->isSsl();
        if ($request_method === "ajax" || $request_method === "xmlhttprequest")
            return $this->isXmlHttpRequest();
        return strtoupper($_SERVER["REQUEST_METHOD"]) === strtoupper($request_method);
    }

    /**
     * 指定されたHTTPリクエストメソッドが利用可能であるかチェックする
     * Check if specified HTTP request method is available
     * 
     * @param string $method_name
     * @return boolean
     */
    protected function _checkAllowedMethod($method_name)
    {
        return in_array(strtoupper($method_name), array_values($this->_http_methods)) &&
                !in_array(strtoupper($method_name), $this->getForbiddenMethods());
    }

    /**
     * フィルタ型が利用可能などうかチェックする
     * Check if filter type is available
     * 
     * @param $_FILTER_
     * @link 設定可能なフィルタの型 <pre>https://www.php.net/manual/ja/filter.filters.php</pre>
     * 
     * @return boolean
     */
    protected function _checkAvailableFilterType($_FILTER_)
    {
        return isset($_FILTER_) && in_array($_FILTER_, $this->getFilterTypeList());
    }

    /**
     * 指定された名前を元にPOSTメソッドからスクリプトに渡された値を取得する
     * Gets the value passed from the POST method to the script based on the specified name
     * 
     * @param string $name
     * @param $_FILTER_
     * @link 設定可能なフィルタの型 <pre>https://www.php.net/manual/ja/filter.filters.php</pre>
     * 
     * @return mixed
     */
    public function getPost($name, $_FILTER_ = null)
    {
        if (isset($_POST[$name]))
            return function_exists("filter_input") ?
                    filter_input(INPUT_POST, $name, $this->_checkAvailableFilterType($_FILTER_) ? $_FILTER_ : FILTER_DEFAULT) : $_POST[$name];
        return null;
    }

    /**
     * 指定された名前を元にGETメソッドからスクリプトに渡されたクエリパラメータを取得する
     * Get query parameter passed to script from GET method based on specified name
     * 
     * @param string $name
     * @param $_FILTER_
     * 
     * @link 設定可能なフィルタの型 <pre>https://www.php.net/manual/ja/filter.filters.php</pre>
     * 
     * @return mixed
     */
    public function getQuery($name, $_FILTER_ = null)
    {
        if (isset($_GET[$name]))
            return function_exists("filter_input") ?
                    filter_input(INPUT_GET, $name, $this->_checkAvailableFilterType($_FILTER_) ? $_FILTER_ : FILTER_DEFAULT) : $_GET[$name];
        return null;
    }

    /**
     * 禁止されたHTTPリクエストメソッドの一覧を配列で取得する
     * Get a list of forbidden HTTP request methods as an array
     * 
     * @return array
     */
    public function getForbiddenMethods()
    {
        return array_map("strtoupper", array_values($this->_forbiddenMethods));
    }

    /**
     * 禁止するHTTPリクエストメソッドを設定する、HTTPメソッドとして定義されていないものは設定されない
     * Set the forbidden HTTP request method,Those not defined as HTTP methods are not set
     * 
     * @param string $method_name
     */
    public function setForbiddenMethod($method_name)
    {
        $this->_checkAllowedMethod($method_name) && $this->_forbiddenMethods[] = strtoupper($method_name);
    }

    /**
     * 禁止するHTTPリクエストメソッド群を配列で設定する
     * Set the forbidden HTTP request methods in the array
     * 
     * @param array $method_list
     * @return array
     */
    public function setForbiddenMethods(array $method_list)
    {
        return is_callable([$this, rtrim(__FUNCTION__, "s")]) && array_map([$this, rtrim(__FUNCTION__, "s")], $method_list);
    }

    /**
     * 利用可能なフィルタ型のリストを配列で取得する
     * Get a list of available filter types as an array
     * 
     * @return array
     */
    public function getFilterTypeList()
    {
        return (array) $this->_filterTypeList;
    }

    /**
     * 指定されたキーを元にリクエストヘッダ値を取得する
     * Get request header value based on specified key
     * 
     * @param string $name
     * @return mixed
     */
    public function getHeader($name)
    {
        if (function_exists("apache_request_headers"))
            return array_key_exists($name, apache_request_headers()) ? apache_request_headers() : null;
        return null;
    }

    /**
     * リクエストヘッダをすべて配列で取得する
     * Get all request headers in an array
     * 
     * @return array
     */
    public function getHeaders()
    {
        return function_exists("apache_request_headers") ? apache_request_headers() : [];
    }

    /**
     * クライアントIPアドレスを取得する
     * Get client ip address
     * 
     * @return string
     */
    public function getIpAddress()
    {
        return function_exists("filter_input") ? filter_input(INPUT_SERVER, "REMOTE_ADDR", FILTER_VALIDATE_IP) : $_SERVER["REMOTE_ADDR"];
    }

    /**
     * クライアントのホスト名を取得
     * Get client host name
     * 
     * @return mixed
     */
    public function getHostName()
    {
        return gethostbyaddr($this->getIpAddress());
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
