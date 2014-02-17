<?php
require_once 'inc/db.class.php';
require_once 'inc/constants.php';

$db = new DB(DATABASE_SERVER,DATABASE_USER,DATABASE_PASSWORD,DATABASE_DB);

if(!$_GET['count'])
	die("must provide 'count'");
if($_GET['success'])
{
	$sql = 'SELECT * FROM seantbur_query WHERE success='.$_GET['success'].' ORDER BY time DESC LIMIT '.$_GET['count'];
	$db->query($sql);
}
else{	
$sql = 'SELECT * FROM seantbur_query ORDER BY time DESC LIMIT '.$_GET['count'];
$db->query($sql);
}

echo json_encode($db->resultToArray());

?>