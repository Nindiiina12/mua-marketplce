<?php
session_start();
session_destroy();
header("Location: mua_login.php");
exit();
?>
