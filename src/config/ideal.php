<?php
/*
HOW TO CREATE YOUR SELFSIGNED CERTIFICATES

sudo openssl genrsa -aes128 -out certificate.pem -passout pass:YOUR_CHOOSEN_PASSWORD 2048

sudo openssl req -x509 -sha256 -new -key certificate.pem -passin pass:YOUR_CHOOSEN_PASSWORD -days 1825 -out certificate.cer

*/
return array(
    'provider_url'                 => '',
    'acquirer_cert'                => '',
    'merchant_id'                  => '',
    'merchant_cert'                => '',
    'merchant_priv_key'            => '',
    'merchant_priv_key_passwd'     => '',
    'merchant_return_url'          => '',
    'merchant_issuer'              => '',
    'cacert'                       => ''
);