<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01.10.2017
 * Time: 22:58
 */

namespace Tests\Unit;

use App\Library\Parsers\ConfigFilesParser;
use PHPUnit\Framework\TestCase;

class ConfigFilesParserTest extends TestCase
{

    public function testParseRawConfig()
    {

        include "ExampleNginxResponse.php";

        $parser = new ConfigFilesParser();
        $configs = $parser->parseRawConfig($rawConfig);
        $this->assertCount(5, $configs);
        $this->assertEquals($configs[4]->path, '/etc/nginx/sites-enabled/example.conf');
        $this->assertEquals($configs[4]->fileName, 'example.conf');
        $this->assertEquals($configs[4]->domainName, 'domainname.net');

    }
}
