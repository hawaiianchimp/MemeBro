<?php
require_once 'mogreet.class.php';

define(MOGREET_CLIENT_ID, '1080');
define(MOGREET_TOKEN, '3e6b849ff067193e2f03ba09fe31b158');
define(MOGREET_SMS, '23821');
define(MOGREET_MMS, '23790');
define(MOGREET_FROM_NAME, '');
define(MOGREET_FROM, '');

$mogreet = new Mogreet(MOGREET_CLIENT_ID,MOGREET_TOKEN,MOGREET_SMS,MOGREET_MMS);

if($_GET)
{
	$output = $mogreet->mms($_GET['to'], $_GET['message']);
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<header>
<title>MemeBro Mogreet</title>
</header>
<h1>Mogreet</h1>
<form name="input" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
<input type="text" name="to" value="<?php echo $_GET['to']; ?>" placeholder="to"/><br>
<input type="text" name="message" value="<?php echo $_GET['message']; ?>" placeholder="image url"/><br>
<input type="submit" value="Submit"/><br>
<?php echo $output; ?>
</form>
</html>