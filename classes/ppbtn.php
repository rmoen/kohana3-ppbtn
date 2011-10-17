<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @author	Rob Moen
 * @class	Generates Encrypted Paypal Btn
 *
 * Make Company Key and Cert
 * openssl genrsa -out company-key.pem 1024
 * openssl req -new -key company-key.pem -x509 -days 365 -out company-cert.pem
 *
 * Upload pub cert to paypal.
 * Download paypal public cert.
 * Block unencrypted payment buttons
 *	
 */
 
 	class Ppbtn{
 	
 		public $keyfile;
 		public $certfile;
 		public $paypal_certfile;
		public $openssl;
 	
		public function __construct(){

			$this->keyfile = Kohana::config('ppbtn.keyfile');
	 		$this->certfile = Kohana::config('ppbtn.certfile');
	 		$this->paypal_certfile = Kohana::config('ppbtn.ppcertfile');
			$this->openssl = Kohana::config('ppbtn.openssl');
			
 			//make sure some things are set here
			if (!file_exists($this->keyfile)) {
				die("key file: {$this->keyfile} not found");
			}
			if (!file_exists($this->certfile)) {
				die("cert file: {$this->certfile} not found");
			}
			if (!file_exists($this->paypal_certfile)) {
				die("paypal cer:t file {$this->paypal_certfile} not found");
			}
			if (!file_exists($this->paypal_certfile)) {
				die("{$this->openssl} not found");
			}
 		}
 	
		public function getBtn($item){			
			$hash = array('cmd' => '_xclick',
			        'business' => Kohana::config('ppbtn.business'),
			        'cert_id' => Kohana::config('ppbtn.cert_id'),
			        'lc' => Kohana::config('ppbtn.lang'),
			        'custom' => isset($item->custom) ? $item->custom : '',
			        'invoice' => $item->invoiceID,
			        'currency_code' => 'USD', 
			        'no_shipping' => $item->no_shipping,
			        'item_name' => $item->name,
			        'item_number' => $item->number,
					'amount' => $item->amount
				);

			//encrypt btn
			$encrypted = $this->paypal_encrypt($hash);
			//get html for btn
			return $this->returnBtnHTML($encrypted);
 		}	

		public function paypal_encrypt($hash){
			//Build Notation
			$hash['bn']= Kohana::config('ppbtn.bn').'PHP_EWP2';
		
			$data = "";
			foreach ($hash as $key => $value) {
				if ($value != "") {
					$data .= "$key=$value\n";
				}
			}
		
			$openssl_cmd = "(".$this->openssl." smime -sign -signer ".$this->certfile." -inkey ".$this->keyfile." " .
								"-outform der -nodetach -binary <<_EOF_\n".$data."\n_EOF_\n) | " .
								$this->openssl." smime -encrypt -des3 -binary -outform pem ".$this->paypal_certfile;
			echo $openssl_cmd;
			exec($openssl_cmd, $output, $error);
		
			if (!$error) {
				return implode("\n",$output);
			} else {
				return "failed encryption";
			}
		}
		
		public function returnBtnHTML($encrypted){

$form = "
<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target=_blank>
<input type='hidden' name='cmd' value='_s-xclick'>
<input type='hidden' name='encrypted' value='{$encrypted}'>
<input type='submit' value='Pay'>
</form>		
";
			

			return $form;			
		}
 	} //end class
?>