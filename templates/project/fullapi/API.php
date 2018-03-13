<?php

class API implements Phalcon\Di\InjectionAwareInterface
{

    protected $di;
    private $options;
    private $headers;

    public function __construct()
    {
        $this->options = [
            'timeout' => 3,
            'connect_timeout' => 2,
            'follow_redirects' => false,
            'useragent' => 'maibo-requests/1.0',
        ];
        $this->headers = [
            'Content-Type' => 'application/json',
            'X-Request-Id' => $_SERVER['HTTP_X_REQUEST_ID'],
            'X-Forwarded-For' => $_SERVER['HTTP_X_FORWARDED_FOR'],
            'X-Real-Ip' => $_SERVER['HTTP_X_REAL_IP'],
            'X-Maibo-App-Name' => $_SERVER['HTTP_X_MAIBO_APP_NAME'],
        ];
    }

    //业务请求实例
    public function users($action, &$result, $data)
    {
        switch ($action) {
            case 'login':
                $method = 'post';
                break;
            case 'register':
                $method = 'post';
                break;
        }

        $url = getenv('USERS_BASE_API') . '/' . $action;
        if (!$this->request($method, $url, $result, $data)) {
            return false;
        }
        return true;
    }

    //对网络请求成功返回的数据进一步进行业务错误代码判断和日志记录
    private function request($method, $url, &$result, $data=null, $headers=[])
    {
        $method = strtoupper($method);
        if (in_array($method, ['POST', 'PUT', 'OPTIONS', 'PATCH'])) {
            $data = json_encode($data);
        }

        $status = $this->__request(
            $method, $url, $result, $data, $headers
        );

        if ($status === false) {
            $this->di->getLogger()->error(
                $_SERVER['HTTP_X_REQUEST_ID'] . '|' .
                $method . ' ' . $url . ',' . json_encode($data) . ',' .
                json_encode($headers) . '|' .
                json_encode($result)
            );
            return false;
        }

        if (isset($result['errcode']) || isset($result['status']) && $result['status'] != 'ok') {
            $this->di->getLogger()->error(
                $_SERVER['HTTP_X_REQUEST_ID'] . '|' .
                $method . ' ' . $url . ',' . json_encode($data) . ',' .
                json_encode($headers) . '|' .
                json_encode($result)
            );
            return false;
        }

        return true;
    }

    //底层请求及网络错误处理
    private function __request($method, $url, &$result, $data='', $headers=[])
    {
        $response = Requests::request($url, array_merge($this->headers, $headers), $data, $method, $this->options);
        if ($response->success) {
            $result = json_decode($response->body, true);
            return true;
        }
        $result = [
            'server_error',
            [
                'code' => $response->status_code,
                'message' => STATUS['server_error']['message'],
                'type' => 'server_error',
            ],
        ];
        return false;
    }

    public function setDi(Phalcon\DiInterface $di)
    {
        $this->di = $di;
    }

    public function getDi()
    {
        return $this->di;
    }

}
