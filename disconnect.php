<?php

require_once('includes/header.php');

session_unset();
session_destroy();

header('Location:connect.php');


require_once('includes/footer.php');



?>