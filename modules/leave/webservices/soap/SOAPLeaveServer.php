<?php
/* THIS ENSURES WE ARE ABLE TO CONTROL OUR INCLUDE FILES */
define( '_VALID_DIR_', 1 );

// call library
require_once ( '../../../../include/nusoap/nusoap.php' );
require_once ( '../../../../config.php' );
require_once $GLOBALS['dr']."modules/leave/classes/user_balances.php";

// create instance
$server = new soap_server();

// initialize WSDL support
$server->configureWSDL( 'SOAPLeaveServer' , 'urn:SOAPLeaveServer' );

// place schema at namespace with prefix tns
$server->wsdl->schemaTargetNamespace = 'urn:SOAPLeaveServer';

// register methode
$server->register('AboutService', // method name
				   array(),// input parameter
				   array('return'=>'xsd:string'),// output
				   'urn:SOAPLeaveServer' ,// namespace
				   'urn:SOAPLeaveServer#hello',// soapaction
				   'rpc',// style
				   'encoded',// use
				   'Use this service to create and deal with the leave module'// documentation
);

$server->register('GetUserBalance', // method name
					array('login'=>'xsd:string'),// input parameter
					array('return'=>'xsd:string'),// output
					'urn:SOAPLeaveServer' ,// namespace
					'urn:SOAPLeaveServer#hello',// soapaction
					'rpc',// style
					'encoded',// use
					'Get the total days leave of a user'// documentation
					);

// define method as function
function AboutService() {
	$c="Use this SOAP server to find out more about this module";
	return $c;
}
function GetUserBalance($u="") {
	$c = "";

	$db=$GLOBALS['db'];

		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."core_user_master um
					WHERE login = '".EscapeData($u)."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$user_id = $row['user_id'];
			}
		}

		$ub = new UserBalances;
		$ub->SetParameters($user_id);


	return "Done";
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

 ?>
