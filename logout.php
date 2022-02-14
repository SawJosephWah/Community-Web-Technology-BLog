<?php

require_once 'core/autoload.php';

unset($_SESSION['vlog_user_session']);
// session_destroy();

Helper::redirect('login.php');

?>