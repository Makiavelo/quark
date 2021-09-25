<?php

use PHPUnit\Framework\TestCase;

use Makiavelo\Quark\View;

final class ViewTest extends TestCase
{
    public function testCreate()
    {
        $path = dirname(__FILE__) . '/data/template_1.php';
        $view = new View($path);
        $this->assertEquals($view->template, $path);
    }

    public function testBadCreate()
    {
        $this->expectException(\Exception::class);

        new View('bad/template_12.php');
    }

    public function testFetchAndRender()
    {
        $path = dirname(__FILE__) . '/data/template_1.php';
        $view = new View($path);
        $content = $view->fetch();

        $this->assertEquals($content, 'template_1');
    }

    public function testTemplateVars()
    {
        $path = dirname(__FILE__) . '/data/template_2.php';
        $view = new View($path);
        $content = $view->fetch([
            'name' => 'John'
        ]);

        $this->assertEquals($content, 'John');
    }
}