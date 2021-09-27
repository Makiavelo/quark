<?php

use PHPUnit\Framework\TestCase;

use \Mockery as m;
use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Route;

final class RouteTest extends TestCase
{
    protected function tearDown() : void {
        \Mockery::close();
    }

    public function fakeUrl($url, $method)
    {
        $req = m::mock('request');
        $req->shouldReceive('path')
            ->andReturn($url);

        $req->shouldReceive('method')
            ->andReturn($method);

        Quark::app()->request = $req;
    }

    public function testEmtpyUrl()
    {
        $this->fakeUrl('/', 'GET');

        $route = new Route([
            'method' => 'GET',
            'path' => '/'
        ]);

        $this->assertTrue($route->match());
    }

    public function testUrlWithoutParameters()
    {
        $this->fakeUrl('/providers', 'GET');

        $route = new Route([
            'method' => 'GET',
            'path' => '/providers'
        ]);

        $this->assertTrue($route->match());
    }

    public function testUrlWithParameters()
    {
        $this->fakeUrl('/provider/12', 'GET');

        $route = new Route([
            'method' => 'GET',
            'path' => '/provider/@id'
        ]);

        $this->assertTrue($route->match());
        $this->assertEquals($route->params['id'], '12');
    }

    public function testUrlWithMoreParameters()
    {
        $this->fakeUrl('/provider/12/place/15/edit', 'GET');

        $route = new Route([
            'method' => 'GET',
            'path' => '/provider/@provider_id/place/@place_id/edit'
        ]);

        $this->assertTrue($route->match());
        $this->assertEquals($route->params['provider_id'], '12');
        $this->assertEquals($route->params['place_id'], '15');
    }
}