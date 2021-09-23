<?php

use PHPUnit\Framework\TestCase;

use \Mockery as m;
use Makiavelo\Quark\Request;

final class RequestTest extends TestCase
{
    protected function tearDown() : void {
        \Mockery::close();
        //Request::resetInstance();
    }

    public function testParams()
    {
        Request::resetInstance();
        $req = Request::get();
        $req->params['test_id'] = 12;
        $this->assertEquals($req->param('test_id'), 12);
    }

    public function testPath()
    {
        $req = Request::get();
        $_SERVER['PATH_INFO'] = '/test/case';

        $this->assertEquals($req->path(), '/test/case');
    }

    public function testMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        $req = Request::get();
        $this->assertEquals($req->method(), 'POST');
    }

    public function testPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'test_param' => '1234'
        ];

        Request::resetInstance();
        $req = Request::get();
        $this->assertEquals($req->post('test_param'), '1234');
    }

    public function testGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = [
            'test_param' => '1234'
        ];

        Request::resetInstance();
        $req = Request::get();
        $this->assertEquals($req->query('test_param'), '1234');
    }

    public function testScheme()
    {
        $_SERVER['HTTPS'] = 'on';
        $req = Request::get();
        $this->assertEquals($req->getScheme(), 'https');

        $_SERVER['HTTPS'] = 'off';
        $req = Request::get();
        $this->assertEquals($req->getScheme(), 'http');
    }

    public function testPathParams()
    {
        $params = [
            'test_param' => '1234',
            'another' => 'abcd'
        ];

        $req = Request::get();
        $req->addPathParams($params);

        $this->assertEquals($req->params['test_param'], '1234');
        $this->assertEquals($req->params['another'], 'abcd');

        $this->assertEquals($req->pathParams['test_param'], '1234');
        $this->assertEquals($req->pathParams['another'], 'abcd');
    }
}