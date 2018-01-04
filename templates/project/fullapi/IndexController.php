<?php

class IndexController extends ControllerBase
{

    protected function addRules() {
        $this->rules = [
            /* addRules举例
                'mobile' => new \Phalcon\Validation\Validator\Regex(
                    [
                        'pattern' => '/^1[3-9][0-9]{9}$/',
                        'code' => VALID_ERROR,
                        'message' => STATUS['mobile_invalid']['message'],
                    ]
                ),
                'password' => new \Phalcon\Validation\Validator\StringLength(
                    [
                        'min' => 6,
                        'max' => 16,
                        'messageMaximum' => STATUS['password_invalid']['message'],
                        'messageMinimum' => STATUS['password_invalid']['message'],
                        'code' => VALID_ERROR,
                    ]
                ),
                'name' => new \Phalcon\Validation\Validator\StringLength(
                    [
                        'min' => 1,
                        'max' => 8,
                        'messageMaximum' => STATUS['user_name_invalid']['message'],
                        'messageMinimum' => STATUS['user_name_invalid']['message'],
                        'code' => VALID_ERROR,
                    ]
                ),
            */
        ];
    }

}

