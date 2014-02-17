<?php
require_once 'inc/memegenerator.class.php';
require_once 'inc/constants.php';

$memegenerator = new MemeGenerator(MEMEGENERATOR_USERNAME,MEMEGENERATOR_PASSWORD);

if(strstr($_GET['query'], 'pop'))
{
	echo $memegenerator->popularMemes();
}

elseif(strstr($_GET['query'], 'tre'))
{
	echo $memegenerator->trendingMemes();
}

elseif(strstr($_GET['query'], 'new'))
{
	echo $memegenerator->newMemes();
}

elseif(strstr($_GET['query'], 'search'))
{
	if($_GET['term'])
	{
		echo $memegenerator->searchMemes($_GET['term']);
	}
	else
	{
		echo 'Must provide a query';
	}
}
else
{
	echo 'Must provide a query';
}


?>