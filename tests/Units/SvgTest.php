<?php

namespace YonisSavary\Sharp\Extensions\Tests\Units;

use PHPUnit\Framework\TestCase;
use YonisSavary\Sharp\Classes\Http\Request;
use YonisSavary\Sharp\Classes\Http\Response;
use YonisSavary\Sharp\Core\Utils;
use YonisSavary\Sharp\Extensions\AssetsKit\Components\Svg;

class SvgTest extends TestCase
{
    protected function createSvgProvider(): Svg
    {
        $svg = new Svg();
        $svg->setConfiguration(["path" => realpath(Utils::relativePath("../../vendor/twbs/bootstrap-icons/icons"))]);
        return $svg;
    }

    public function test_handleRequest()
    {
        $svg = $this->createSvgProvider();
        $req = new Request("GET", "/assets/svg", ["name" => "person"]);

        $this->assertInstanceOf(
            Response::class,
            $svg->handleRequest($req, true)
        );
    }


    public function test_serve()
    {
        $svg = $this->createSvgProvider();
        $req = new Request("GET", "/assets/svg", ["name" => "person"]);

        $this->assertInstanceOf(
            Response::class,
            $svg->serve($req)
        );
    }

    public function test_get()
    {
        $svg = $this->createSvgProvider();
        $svgContent = $svg->get("person");

        $this->assertStringContainsString("<svg", $svgContent);
    }

    public function test_exists()
    {
        $svg = $this->createSvgProvider();

        $this->assertTrue($svg->exists("person"));
        $this->assertTrue($svg->exists("file"));
        $this->assertFalse($svg->exists("file-exclamation-triange"));
    }
}