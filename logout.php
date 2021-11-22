<?php

session_start();

require_once('bootstrap.php');

$_SESSION = [];

redirect_to_main();

exit();
