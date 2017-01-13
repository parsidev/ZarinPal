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

    private function createClient()
    {
        $url = $this->confg['webServiceUrl'];
        $this->client = new SoapClient($url, array('encoding' => 'UTF-8'));
    }

    public function PaymentRequest($price, $description, $email, $phone, $callBack, $isZarinGate = true)
    {
        $this->createClient();

        $param = [
            'MerchantID' => $this->confg['merchantId'],
            'Amount' => $price,
            'Description' => $description,
            'Email' => $email,
            'Mobile' => $phone,
            'CallbackURL' => $callBack
        ];
        $response = $this->client->PaymentRequest($param);
        $response->PayUrl = "https://www.zarinpal.com/pg/StartPay/" . $response->Authority;
        if($isZarinGate)
            $response->PayUrl = $response->PayUrl . "/ZarinGate";
        return $response;
    }

    public function PaymentVerification($authority, $price)
    {
        $this->createClient();
        $param = array(
            'MerchantID' => $this->confg['merchantId'],
            'Authority' => $authority,
            'Amount' => $price
        );
        return $this->client->PaymentVerification($param);
    }

}
