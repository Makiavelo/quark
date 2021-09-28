<?php

namespace Makiavelo\Quark;

class View
{
    /**
     * View variables.
     *
     * @var array
     */
    public $vars = [];

    /**
     * Template file.
     *
     * @var string
     */
    public $template;

    /**
     * Constructor.
     *
     * @param string $path Path to templates directory
     * @param string $vars
     */
    public function __construct($path, $vars = []) {
        if (!file_exists($path)) {
            throw new \Exception("Template file not found: {$path}.");
        } else {
            $this->vars = $vars;
            $this->template = $path;
        }
    }

    /**
     * Outputs a template.
     *
     * @param array $data Template data
     */
    public function render($data = []) {
        if (is_array($data)) {
            $this->vars = array_merge($this->vars, $data);
        }

        extract($this->vars);

        include $this->template;
    }

    /**
     * Gets the output of a template.
     *
     * @param array $data Template data
     * @return string Output of template
     */
    public function fetch($data = []) {
        ob_start();

        $this->render($data);
        $output = ob_get_clean();

        return $output;
    }

    public function __toString()
    {
        return $this->fetch();
    }
}