<?php

session_start();

session_destroy();

header("Location: /Web Ventas/login.php");

exit;

?>