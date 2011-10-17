<?php defined('SYSPATH') OR die('No direct access allowed.');

//use APPPATH to ref application directory 
return array(
	'lang' 	     => 'US',
	'openssl'    => '/usr/bin/openssl',
	'bn'         => 'CompanyName',  //build notation
	'business'   => 'seller@domain.com',  //seller account
	'cert_id'    => '1234', // cert id, obtained from paypal.
    'keyfile'    => APPPATH.'/vendor/paypalcerts/company-key.pem', //location of required cert / key files
    'certfile'   => APPPATH.'/vendor/paypalcerts/company-cert.pem', //location of cert
    'ppcertfile' => APPPATH.'/vendor/paypalcerts/company-paypal-cert.pem' //location of signed paypal public certificate
);
