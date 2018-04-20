<?php

class ModelBase extends \Phalcon\Mvc\Model
{

    public function initialize()
    {
        //读写分离设置
        //$this->setReadConnectionService('dbRead');
        //$this->setWriteConnectionService('dbWrite');

        //仅向数据库传递改变了的字段
        $this->useDynamicUpdate(true);

        $this->addBehavior(
            new \Phalcon\Mvc\Model\Behavior\Timestampable(
                [
                    'beforeCreate' => [
                        'field' => 'created_at',
                        'format'=> 'Y-m-d H:i:s',
                    ],
                ]
            )
        );
    }

}

