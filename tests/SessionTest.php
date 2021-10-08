<?php

use PHPUnit\Framework\TestCase;

use Makiavelo\Quark\Util\Session;

final class SessionTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $_SESSION = array();   
    }

    public function testInstance()
    {
        $session = Session::get();
        $this->assertInstanceOf('Makiavelo\\Quark\\Util\\Session', $session);
    }

    public function testSetGet()
    {
        
        $session = Session::get();

        $session->set('logged_in', true);

        $this->assertEquals(true, $session->param('logged_in'));

        $session->set('some->var->path', 'test');
        $this->assertEquals('test', $session->param('some->var->path'));
    }
}
