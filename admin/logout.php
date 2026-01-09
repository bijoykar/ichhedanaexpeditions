<?php
require_once __DIR__ . '/../config/config.php';

// Destroy session
session_destroy();

// Redirect to login
redirect(ADMIN_URL . '/login.php');
