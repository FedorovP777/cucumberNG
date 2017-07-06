<?php

namespace App;

use function foo\func;
use Illuminate\Database\Eloquent\Model;

class NginxServer extends Model
{

    public $rawResult = '';
    public $criticalError = false;
    public $errors = [];
    private $errorObj = ['typeError' => null, 'configFile' => null, 'rowError' => null];


    public function checkConfig()
    {

        $this->rawResult = shell_exec("nginx -t 2>&1");

        $this->parseCheckResult();

    }


    private function parseCheckResult()
    {

        $re = '/^.*\[(emerg|warn|error)\].*$/m';
        preg_match_all($re, $this->rawResult, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $itemError) {


            $obj = (object)$this->errorObj;
            $obj->typeError = $itemError[1];


            $obj->configFile = $this->parseFileInError($itemError[0])[1];
            $obj->rowError = $this->parseFileInError($itemError[0])[2];
            //   var_dump($obj);
            array_push($this->errors, $obj);
        }


    }

    private function parseFileInError($str)
    {

        $re = '/in\s(.*):([[:digit:]]*)/m';
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

        if (sizeof($matches) > 0) {

            return $matches[0];
        }
        return [null, null, null];

    }

    public function reloadConfig()
    {


        return shell_exec(" /etc/init.d/nginx reload");


    }

}
