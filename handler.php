<?php

require_once 'inc/memebro.class.php';
require_once 'inc/gvmax.class.php';
require_once 'inc/memegenerator.class.php';
require_once 'inc/yourls.class.php';
require_once 'inc/DB.class.php';
require_once 'inc/mogreet.class.php';
require_once 'inc/constants.php';

//$_POST['number'] = '9493719225';
//$_POST['text'] = '+pop';
$db = new DB(DATABASE_SERVER,DATABASE_USER,DATABASE_PASSWORD,DATABASE_DB);
$gvmax = new GVMax(GVMAX_API);
$memegenerator = new MemeGenerator(MEMEGENERATOR_USERNAME,MEMEGENERATOR_PASSWORD);
$yourls = new Yourls(SITE, YOURLS_API);
$mogreet = new Mogreet(MOGREET_CLIENT_ID,MOGREET_TOKEN,MOGREET_SMS,MOGREET_MMS);
$memebro = new MemeBro($gvmax, $memegenerator, $yourls, $db, $mogreet);

$memebro->process();

echo json_encode($memebro);
?>