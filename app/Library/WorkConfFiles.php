<?php

namespace App\Library;

use App\Library\NginxCLI;
use App\Library\Parsers\ConfigFilesParser;

class WorkConfFiles
{

    public function getAllFiles(): \Illuminate\Support\Collection
    {

        $nginxCLI = new NginxCLI();
        $rawConfigFiles = $nginxCLI->getRawConfig();

        $configParser = new ConfigFilesParser();
        $configFilesList = $configParser->parseRawConfig($rawConfigFiles);
        $this->assocId($configFilesList);

        return $configFilesList;
    }


    private function assocId(&$configFiles)
    {

        $configFiles = $configFiles->map(function ($item, $key) {

            $item->id = hash('ripemd160', $item->path);

            return $item;
        });

    }

    public function getFile($fileId)
    {
        $allFiles = $this->getAllFiles();
        $file = $allFiles->where('id', $fileId)->first();
        $file->body = $this->readFile($file->path);

        return $file;
    }

    private function readFile($filePath)
    {

        return file_get_contents($filePath);
    }

}