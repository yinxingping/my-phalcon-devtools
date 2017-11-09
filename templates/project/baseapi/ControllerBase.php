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

        $this->writeDbLog();

        $this->response->send();
    }

    protected function getMessages(\Phalcon\Mvc\Model &$model)
    {
        $messages = [];
        foreach ($model->getMessages() as $message) {
            $messages[] = [
                'field' => $message->getField(),
                'type' => $message->getType(),
                'code' => $message->getCode(),
                'message' => $message->getMessage(),
            ];
        }

        return $messages;
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

    // 记录SQL日志
    private function writeDbLog()
    {
        $config = $this->getDI()->getConfig();
        $profiler = $this->profiler;

        if ($profiler->getNumberTotalStatements() != 0) {
            $profiles = $profiler->getProfiles();
            $dbLogger = Phalcon\Logger\Factory::load($config['dbLogger']);

            $logStr = '|sqlTotal:' . $profiler->getNumberTotalStatements() . ',msTotal:' . round($profiler->getTotalElapsedSeconds() * 1000, 3);
            foreach ($profiles as $profile) {
                $logStr .= '|sql:' . $profile->getSQLStatement() . '|ms:' . round($profile->getTotalElapsedSeconds() * 1000, 3);
            }

            $dbLogger->info($logStr);
        }
    }
}

