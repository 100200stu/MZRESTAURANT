<?php
session_start();
session_destroy();
header('Location: ../pages/admin_login.html');
exit;