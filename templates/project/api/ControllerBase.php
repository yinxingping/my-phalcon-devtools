<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->sendContent('ok','测试成功');
    }

    protected function sendContent($status, $messages=null)
    {
        $messages = $messages ?? STATUS[$status]['message'] ?? '无话可说';

        $this->response->setJsonContent([
            'code' => STATUS[$status]['code'],
            'status' => $status,
            'detail' => $messages,
        ]);

        $this->response->send();
    }

    protected function getJsonRawBody(Array $filter = [])
    {
        $params = $this->request->getJsonRawBody();
        foreach ($params as $k=>$v) {
            if (!in_array($k, $filter)) {
                unset($params->$k);
                continue;
            }
            $params->$k = trim($v);
        }

        return $params;
    }

}

