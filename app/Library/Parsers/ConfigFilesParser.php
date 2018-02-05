<?php

namespace App\Library\Parsers;


class ConfigFilesParser
{

    public function parseRawConfig($rawText)
    {

        $confPart = explode('# configuration file ', $rawText);
        $this->deleteFirstEmptyLine($confPart);
        $result = collect();
        foreach ($confPart as $configFile) {

            $confObj = (object)[];
            $confFileLines = explode("\n", $configFile);

            $confObj->path = $this->getFilenameConf($confFileLines);

            $confObj->fileName = basename($confObj->path);

            $content = $this->getTextConfig($confFileLines);

            $confObj->domainName = $this->parseHostNames($content);

            $result->push($confObj);
        }

        return $result;

    }

    private function deleteFirstEmptyLine(array &$fileLines)
    {
        unset($fileLines[0]);
    }

    private function getFilenameConf(array &$fileLines): string
    {
        $filename = array_shift($fileLines);

        return substr(trim($filename), 0, -1); //delete ':' in line
    }

    private function getTextConfig(array $confFileLines): string
    {

        return implode("\n", $confFileLines);
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
}