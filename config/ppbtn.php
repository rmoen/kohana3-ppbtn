<?php defined('SYSPATH') OR die('No direct access allowed.');

//use APPPATH to ref application directory 
return array(
    'config' => array(
		'lang' 	     => 'US',
		'openssl'    => '/usr/bin/openssl',
    	'bn'         => 'CompanyName',  //build notation
    	'business'   => 'seller@domain.com',  //seller account
		'cert_id'    => '1234567890123', // cert id, obtained from paypal.
        'keyfile'    => APPPATH.'/paypal/company-key.pem', //location of required cert / key files
        'certfile'   => APPPATH.'/paypal/company-cert.pem', //location of cert
        'ppcertfile' => APPPATH.'/paypal/paypal-cert.pem' //location of signed paypal public certificate
    ),
);
