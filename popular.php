<?php
require_once 'inc/memegenerator.class.php';
require_once 'inc/constants.php';

$memegenerator = new MemeGenerator(MEMEGENERATOR_USERNAME,MEMEGENERATOR_PASSWORD);

echo $memegenerator->popularMemes();

?>