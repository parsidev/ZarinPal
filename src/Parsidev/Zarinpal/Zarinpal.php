<?php

namespace Parsidev\Zarinpal;

use SoapClient;

class Zarinpal {

    protected $confg;
    protected $client;

    private function createClient($isIran = true) {
        if ($isIran) {
            $url = $this->confg['webServiceUrlIran'];
        } else {
            $url = $this->confg['webServiceUrlGermany'];
        }

        $this->client = new SoapClient($url, array('encoding' => 'UTF-8'));
    }

    public function __construct($config) {
        $this->confg = $config;
    }

    public function getFunctions($isIran = true) {
        $this->createClient($isIran);
        $functions = $this->client->__getFunctions();
        $response = "<ol>\n";
        foreach ($functions as $function) {
            $response .= "\t<li>" . $function . "</li>\n";
        }
        return $response . "</ol>";
    }

}
