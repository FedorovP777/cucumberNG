<?php
declare(strict_types=1);
namespace App;

use Illuminate\Database\Eloquent\Model;

class NginxConfig extends Model
{
    private $rawConfig = '';
    private $configs = [];
    public $path = null;
    public $content = null;
    private $tmpPath = '';

    public function getAllHosts(): array
    {

        $this->getAllConfig();
//        $result = array_filter($this->configs, function ($item) {
//
//            return $item->type === 'host';
//        });

        return $this->configs;
    }

    public function getHost(string $filePath): \stdClass
    {


        $this->getAllConfig();

        $searchedItems = array_filter($this->configs, function ($item) use ($filePath) {

            return ($item->configPathBase64 === $filePath);
        });

        $result = (object)array_values($searchedItems)[0];
        return $result;
    }


    public function saveConfig(): int
    {

        return file_put_contents($this->path, $this->content);
    }

    public function getAllConfig(): bool
    {

        $this->getRawConfig();
        $this->parseRawConfig();


        return true;

    }


    public function backupInTmp(): bool
    {

        $this->tmpPath = '/tmp/'.basename($this->path) . '_' . microtime();

        return copy($this->path, $this->tmpPath);

    }

    public function restoreTmp(): bool
    {
       return copy($this->tmpPath, $this->path);

    }

    private function getTextConfig(array $confFileLines): string
    {

        return implode("\n", $confFileLines);
    }

    public function getFilenameConf(array &$fileLines): string
    {
        $filename = array_shift($fileLines);
        return substr($filename, 0, -1); //delete ':' in line
    }

    private function checkHostsType(string $text): bool
    {

        $re = '/^\s*server\s*\{/m';

        preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);

        if (count($matches) > 0) {

            return true;

        }

        return false;
    }

    private function getTypeConfig(string $fileName, string $text): string
    {


        if ($fileName === 'nginx.conf') {

            return 'mainConf';
        }

        if ($fileName === 'mime.types') {

            return 'mainConf';
        }

        if ($this->checkHostsType($text) === true) {

            return 'host';
        }


        return '';
    }


    private function parseHostNames(string $text): string
    {

        $re = '/^\s*server_name\s*(.*)\;/m';
        preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);

        $result = '';
        foreach ($matches as $hostName) {

            $result = $result . '' . $hostName[1];
        }

        return $result;

    }

    private function deleteFirstEmptyLine(array &$fileLines):NginxConfig
    {

        unset($fileLines[0]);

        return $this;
    }

    private function parseRawConfig(): NginxConfig
    {

        $result = explode('# configuration file ', $this->rawConfig);
        $this->deleteFirstEmptyLine($result);

        foreach ($result as $configFile) {

            $confObj = (object)[];
            $confFileLines = explode("\n", $configFile);

            $confObj->configPath = $this->getFilenameConf($confFileLines);
            $confObj->configPathBase64 = base64_encode($confObj->configPath);
            $confObj->configFileName = basename($confObj->configPath);
            $confObj->content = $this->getTextConfig($confFileLines);
            $confObj->type = $this->getTypeConfig($confObj->configFileName, $confObj->content);

            if ($confObj->type === 'host') {

                $confObj->hostnames = $this->parseHostNames($confObj->content);
            }

            array_push($this->configs, $confObj);
        }

        return $this;

    }

    private function getRawConfig(): NginxConfig
    {
        $rawConf = shell_exec("nginx -T");

        $this->rawConfig = $rawConf;

        return $this;
    }


}
