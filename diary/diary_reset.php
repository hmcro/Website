<?php

// use this to clear the cookie and reset things
unset( $_COOKIE['CID'] );
setcookie('CID', '', time() - 3600);

header('Location: /diary');

?>