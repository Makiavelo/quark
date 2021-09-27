<?php

use PHPUnit\Framework\TestCase;

use \Mockery as m;
use Makiavelo\Quark\Response;

final class ResponseTest extends TestCase
{
    protected function tearDown() : void {
        \Mockery::close();
        Response::resetInstance();
    }

    public function testBody()
    {
        $res = Response::get();

        $instance = $res->body('test body');
        $this->assertEquals($res->body(), 'test body');
        $this->assertEquals(get_class($instance), 'Makiavelo\\Quark\\Response');
    }

    public function testStatus()
    {
        $res = Response::get();
        $status = $res->status();
        $this->assertEquals($status, 200);

        $instance = $res->status(404);
        $this->assertEquals(get_class($instance), 'Makiavelo\\Quark\\Response');

        $this->assertEquals($res->status(), 404);
    }

    public function testHeaders()
    {
        $res = Response::get();
        $res->addHeader('test_header', '1234');

        $this->assertEquals($res->headers['test_header'], '1234');

        $res->clear();

        $res->addHeaders([
            'test_2' => 'abcd',
            'test_3' => '4567'
        ]);

        $this->assertEquals($res->headers['test_2'], 'abcd');
        $this->assertEquals($res->headers['test_3'], '4567');
    }

    public function testClear()
    {
        $res = Response::get();
        $res->status(400);
        $res->body('Some body');
        $res->addHeader('test', '1234');
        $res->clear();

        $this->assertEquals($res->status(), 200);
        $this->assertEquals($res->body, '');
        $this->assertEquals(count($res->headers), 0);
    }

    public function testSend()
    {
        $res = Response::get();
        ob_start();
        $res->send('Some body');
        $output = ob_get_clean();

        $this->assertEquals($res->body, 'Some body');
        $this->assertEquals($output, 'Some body');
    }
}
