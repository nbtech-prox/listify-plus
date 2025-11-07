<?php
require_once '../config/config.php';

session_destroy();
setFlash('success', __('auth_logout_success'));
redirect('/index.php');
