<?php

/**
 * 
 */
class View {

    protected $_response;

    public function __construct()
    {
        $this->_response = new Response();
    }

    /**
     * http-optionsメソッド
     */
    public function options()
    {
        $methods = array('get', 'post', 'put', 'head', 'options', 'delete', 'trace', 'connect', 'patch');
        $result = array();
        foreach ($methods as $method)
            if (method_exists($this, $method))
                $result[] = $method;
        $this->_response->writeHead(200, array('Allow', strtoupper(join(',', $result))));
        $this->_response->end();
    }

    /**
     * 400、500番台レスポンスコード(client or server error)の簡易化メソッド
     */
    public function error($code)
    {
        $this->_response->writeHead($code);
    }

    /**
     * リダイレクション専用メソッド
     */
    public function redirect($url, $code = 302)
    {
        $this->_response->writeHead($code, array('Location' => $url));
        $this->_response->end();
    }

    /**
     * エラーハンドリング
     */
    public function handleException(Exception $e, $debug_mode = false)
    {
        $this->_response->writeHead(500);
        $this->_response->end($debug_mode ? $e->getTraceAsString() : $e->getMessage());
    }

    public function execute()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        try {
            // リクエストメソッドが非対応ならば405, そうでなければ呼び出し
            if (method_exists($this, $method))
                $this->{$method}();
            else
                $this->error(405);
        } catch (Exception $e) {
            $this->handleException($e);
        }
        if (!$this->_response->headersSent)
            $this->error(500);
        return $this->_response;
    }

}

?>