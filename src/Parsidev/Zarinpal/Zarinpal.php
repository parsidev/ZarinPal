<?php

namespace Parsidev\Zarinpal;

use SoapClient;

class Zarinpal {

    protected $confg;
    protected $client;

    private function createClient($isIran) {
        if ($isIran) {
            $url = $this->confg['webServiceUrlIran'];
        } else {
            $url = $this->confg['webServiceUrlGermany'];
        }

        $this->client = new SoapClient($url, array('encoding' => 'UTF-8'));
    }

    private function getMerchantId($isZarinGate) {
        if ($isZarinGate) {
            return $this->confg['merchantIdDedicated'];
        }

        return $this->confg['merchantIdZarinPal'];
    }

    public function __construct($config) {
        $this->confg = $config;
    }

    public function PaymentRequest($price, $description, $email, $phone, $callBack, $isIran = true, $isZarinGate = false) {
        $this->createClient($isIran);

        $param = array(
            'MerchantID' => $this->getMerchantId($isZarinGate),
            'Amount' => $price,
            'Description' => $description,
            'Email' => $email,
            'Mobile' => $phone,
            'CallbackURL' => $callBack
        );
        return $this->client->PaymentRequest($param);
    }

    public function PaymentVerification($authority, $price, $isIran = true, $isZarinGate = false) {
        $this->createClient($isIran);
        $param = array(
            'MerchantID' => $this->getMerchantId($isZarinGate),
            'Authority' => $authority,
            'Amount' => $price
        );
        return $this->client->PaymentVerification($param);
    }

    public function UssdRequest($price, $description, $email, $phone, $callBack, $isIran = true, $isZarinGate = false) {
        $payReq = $this->PaymentRequest($price, $description, $email, $phone, $callBack, $isIran, $isZarinGate);
        $ussdCode = "*770*97*2*" . intval($payReq->Authority) . "#";
        $payReq->UssdCode = urlencode($ussdCode);
        return $payReq;
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
