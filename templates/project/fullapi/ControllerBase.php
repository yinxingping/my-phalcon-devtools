<?php

abstract class ControllerBase extends \Phalcon\Mvc\Controller
{
    protected $validation;
    protected $rules;

    abstract protected function addRules();

    public function onConstruct()
    {
        $this->validation = new \Phalcon\Validation();
        $this->addRules();
    }

    public function indexAction()
    {
        $this->sendContent('ok','测试成功');
    }

    protected function sendContent($status, $messages=null)
    {
        $messages = $messages ?? STATUS[$status]['message'] ?? '成功';

        $this->response->setJsonContent([
            'code' => STATUS[$status]['code'],
            'status' => $status,
            'detail' => $messages,
        ]);

        $this->response->send();
    }

    protected function getMessages($messages)
    {
        $messageArray = [];
        foreach ($messages as $message) {
            $messageArray[] = [
                'field' => $message->getField(),
                'type' => $message->getType(),
                'code' => $message->getCode(),
                'message' => $message->getMessage(),
            ];
        }

        return $messageArray;
    }

    /*
     * 仅对需要过滤的参数进行验证
     */
    protected function getJsonRawBody(Array $filter = [])
    {
        $params = $this->request->getJsonRawBody();
        foreach ($params as $k=>$v) {
            if (!in_array($k, $filter)) {
                unset($params->$k);
                continue;
            }
            if (is_string($v)) {
                $params->$k = trim($v);
            }
        }

        $validators = [];
        foreach ($filter as $field) {
            if (array_key_exists($field, $this->rules)) {
                $validators[] = [$field, $this->rules[$field]];
            }
        }
        $this->validation->setValidators($validators);
        $messages = $this->validation->validate($params);
        if ($messages->count() != 0) {
            $this->sendContent('valid_error', $this->getMessages($messages));
            exit;
        }

        return $params;
    }

}

