<?php

$url=parse_url(getenv("CLEARDB_DATABASE_URL"));

define(DATABASE_SERVER, $url["host"]);
define(DATABASE_USER, $url["user"]);
define(DATABASE_PASSWORD, $url["pass"]);
define(DATABASE_DB, substr($url["path"],1));

define(MOGREET_CLIENT_ID, '1080');
define(MOGREET_TOKEN, '3e6b849ff067193e2f03ba09fe31b158');
define(MOGREET_SMS, '23821');
define(MOGREET_MMS, '23790');

define(MEMEGENERATOR_USERNAME, 'MemeBro');
define(MEMEGENERATOR_PASSWORD,'alphaphi');

define(GVMAX_API, 'd58ce5c8b2c84884a3b490fc06b8f433');
define(YOURLS_API, '4b5a56a8ef');

define(SITE, 'http://memebro.com');

?>
