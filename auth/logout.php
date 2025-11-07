<?php
require_once '../config/config.php';

session_destroy();
setFlash('success', 'You have been logged out.');
redirect('/index.php');
