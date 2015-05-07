<?php

namespace Parsidev\Zarinpal;

class Zarinpal {

    protected $confg;
    protected $client;

    public function __construct($config, $client) {
        $this->confg = $config;
        $this->client = $client;
    }

    public function getFunctions() {
        $functions = $this->client->__getFunctions();
        $response = "<ol>\n";
        foreach ($functions as $function) {
            $response .= "\t<li>" . $function . "</li>\n";
        }
        return $response . "</ol>";
    }

}
