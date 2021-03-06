<?php
require_once('library/constant.php');
include("library/session.php");

/*
From sandbox paypal sends test_ipn as true
*/

$sandbox = isset($_POST['test_ipn']) ? true : false;
$ppHost = $sandbox ? 'www.sandbox.paypal.com' : 'www.paypal.com';
// Set SSL based on config.php
if($sandbox && $sandbox_ssl)
	$ssl = true;
elseif(!$sandbox && $production_ssl)
	$ssl = true;
else
	$ssl = false;

// Read the post from PayPal system and add 'cmd'   
$req = 'cmd=_notify-validate';   
  
// Store each $_POST value in a NVP string: 1 string encoded and 1 string decoded   
$ipn_email = '';  
$ipn_data_array = array();
foreach ($_POST as $key => $value)   
{   
 $value = urlencode(stripslashes($value));   
 $req .= "&" . $key . "=" . $value;   
 $ipn_email .= $key . " = " . urldecode($value) . '<br />';  
 $ipn_data_array[$key] = urldecode($value);
}


// Store IPN data serialized for RAW data storage later
$ipn_serialized = serialize($ipn_data_array);
  
// Validate IPN with PayPal
require_once('library/paypal/validate.php');

// If there was a problem connecting to the database email site admin with the mysql error info and the raw IPN data.  Then exit.

//EMAIL CODE GOES HERE

// Load IPN data into PHP variables
require_once('library/paypal/parse-ipn-data.php');

// Store RAW IPN log in the DB
require_once("library/session.php");
$ret = $database->saveRawIPN($ipn_serialized);
Logger( "PayPal IPN In Root \n".$ipn_serialized, $level )	;

// Check for disputes/chargebacks/chargeback-reversals
if(
   strtoupper($txn_type) == 'NEW_CASE' || 
   strtoupper($payment_status) == 'REVERSED' || 
   strtoupper($payment_status) == 'CANCELED_REVERSAL'
   )
	//require_once('dispute.php');

// Check if this was a mass payment
if(strtoupper($txn_type) == 'MASSPAY')
	require_once('mass-payment.php');

/*This is where we get our transactions
verify the transaction status and store and update the tables as needed.
Now we need to verify if this is a valid IPN
if yes then confirm that this is has our invoice number and this is un processed transaction.
*/
if($valid){
	$payment_amount = $mc_gross;
	$payment_currency = $mc_currency;

	$message = $item_name . ' number '. $item_number .' status '.$payment_status. ' item number '.$item_number . ' amount ' .$payment_amount . ' currency '.$payment_currency .' txn id ' .$txn_id . 'email '.$receiver_email. ' email '.$payer_email. ' INOIVCE '. $invoice;
	Logger( $message, $level )	;
	
	$rtn= $database->updatePayPalTxn($txn_id, $payment_amount, $payment_status, $custom, $invoice);
	Logger( "PayPal IPN In root \n".serialize($rtn));
	if(!empty($rtn) && $rtn['txn_type']=='fund')
	{
		/* code commented to stop fund upload mail to lenders. fund upload mail only will sent on manual upload by admin */
		//$session->sendFundUploadMail($rtn['userid'],$rtn['amount']);
		if($rtn['donation'] > 0) {
			$session->sendDonationMail($rtn['userid'],$rtn['donation']);
			Logger( "PayPal IPN In root Sending donation mail \n");
		}
	}
	elseif(!empty($rtn) && $rtn['txn_type']=='gift')
	{
		$res2 = $session->sendGiftCardMailsToSender($rtn['order_id']);
		$res3 = $session->sendGiftCardMailsToReciever($rtn['order_id']);
		if($rtn['donation'] > 0)
		{
			if($rtn['userid'] !=0)
				$session->sendDonationMail($rtn['userid'],$rtn['donation']);
			else if(!empty($rtn['senderEmail']))
				$session->sendDonationMail($rtn['userid'],$rtn['donation'],$rtn['senderEmail'],$rtn['senderName']);
				/*$session->sendDonationReminderMailToAdmin($rtn['donation']);*/
		}
	}

}else{
	Logger( 'invalid IPN from Paypal', $level )	;

}

?>