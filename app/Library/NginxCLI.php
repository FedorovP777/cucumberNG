<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01.10.2017
 * Time: 21:20
 */

namespace App\Library;


class NginxCLI
{

    public function getRawConfig(): string
    {
        $rawConf = shell_exec("nginx -T");

        return $rawConf;
    }

    public function checkConfig(): string
    {

        $resultCheck = shell_exec("nginx -t 2>&1");

        return $resultCheck;
    }

    public function reloadConfig(): string
    {

        $result = shell_exec("/etc/init.d/nginx reload");

        return $result;
    }
}