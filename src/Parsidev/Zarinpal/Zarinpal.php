<?php

namespace Parsidev\Zarinpal;

use SoapClient;

class Zarinpal
{

    protected $confg;
    protected $client;

    public function __construct($config)
    {
        $this->confg = $config;
    }

    public function PaymentRequest($price, $description, $email, $phone, $callBack, $isIran = true, $isZarinGate = false)
    {
        $this->createClient($isIran);

        $param = array(
            'MerchantID' => $this->confg['merchantId'],
            'Amount' => $price,
            'Description' => $description,
            'Email' => $email,
            'Mobile' => $phone,
            'CallbackURL' => $callBack
        );
        $response = $this->client->PaymentRequest($param);
        if ($isZarinGate) {
            $response->PayUrl = "https://www.zarinpal.com/pg/StartPay/" . $response->Authority . "/ZarinGate";
        } else {
            $response->PayUrl = "https://www.zarinpal.com/pg/StartPay/" . $response->Authority;
        }
        $ussdCode = "*770*97*2*" . intval($response->Authority) . "#";
        $response->UssdCode = ($ussdCode);
        return $response;
    }

    private function createClient($isIran)
    {
        if ($isIran) {
            $url = $this->confg['webServiceUrlIran'];
        } else {
            $url = $this->confg['webServiceUrlGermany'];
        }

        $this->client = new SoapClient($url, array('encoding' => 'UTF-8'));
    }

    public function PaymentVerification($authority, $price, $isIran = true, $isZarinGate = false)
    {
        $this->createClient($isIran);
        $param = array(
            'MerchantID' => $this->confg['merchantId'],
            'Authority' => $authority,
            'Amount' => $price
        );
        return $this->client->PaymentVerification($param);
    }

    public function getFunctions($isIran = true)
    {
        $this->createClient($isIran);
        $functions = $this->client->__getFunctions();
        $response = "<ol>\n";
        foreach ($functions as $function) {
            $response .= "\t<li>" . $function . "</li>\n";
        }
        return $response . "</ol>";
    }

}
