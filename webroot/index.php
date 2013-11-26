<?php
include_once dirname(dirname(__FILE__)).'/app.php';

$whlist = array(917884103111,871347279,918092019,918365959,704266845,258693590);
$id = '917884103111';

if(in_array($id, $whlist)){
	echo 'true';
} else {
	echo 'false';
}