<?php

require_once 'inc/memebro.class.php';
require_once 'inc/gvmax.class.php';
require_once 'inc/memegenerator.class.php';
require_once 'inc/yourls.class.php';

$gvmax = new GVMax('d58ce5c8b2c84884a3b490fc06b8f433');
$memegenerator = new MemeGenerator('MemeBro','alphaphi');
$yourls = new Yourls('http://memebro.com', '4b5a56a8ef');
$memebro = new MemeBro($gvmax, $memegenerator, $yourls);

if($_GET)
{
	$output = $memebro->processSMS($_GET['SMS']);
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<header>
<title>MemeBro</title>
</header>
<h1>MemeBro</h1>
<h2>Make your Meme with Meme Bro</h2>
<form name="input" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
<input type="text" name="SMS" value="<?php echo $_GET['SMS']; ?>" placeholder="SMS"/><br>
<input type="submit" value="Submit"/><br>
<?php echo $output ?><br>
<img src="<?php echo $output; ?>"></form></html>