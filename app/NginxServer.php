<?php
declare(strict_types=1);
namespace App;


use Illuminate\Database\Eloquent\Model;

class NginxServer extends Model
{

    public $rawResult = '';
    public $criticalError = false;
    public $errors = [];
    private $errorObj = ['typeError' => null, 'configFile' => null, 'rowError' => null];


    public function checkConfig(): NginxServer
    {

        $this->rawResult = shell_exec("nginx -t 2>&1");

        $this->parseCheckResult();

        return $this;

    }


    private function parseCheckResult(): NginxServer
    {

        $re = '/^.*\[(emerg|warn|error)\].*$/m';
        preg_match_all($re, $this->rawResult, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $itemError) {


            $obj = (object)$this->errorObj;
            $obj->typeError = $itemError[1];
            $obj->configFile = null;
            $obj->rowError = null;
            $parseRow = $this->parseFileInError($itemError[0]);
            if (sizeof($parseRow) === 2) {
                $parseRow = (object)$parseRow;
                $obj->configFile = $parseRow->fileName;
                $obj->rowError = $parseRow->row;

            }


            array_push($this->errors, $obj);
        }

        return $this;

    }

    private function parseFileInError($str): array
    {

        $re = '/in\s(.*):([[:digit:]]*)/m';
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

        if (sizeof($matches) > 0 && isset($matches[0][1]) && isset($matches[0][2])) {

            return ['fileName' => $matches[0][1], 'row' => $matches[0][2]];
        }


        return [];

    }

    public function reloadConfig(): string
    {


        return shell_exec(" /etc/init.d/nginx reload");


    }

}
