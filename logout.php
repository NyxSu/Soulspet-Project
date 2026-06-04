<?php
// ============================================================
// SoulsPets - Logout
// ============================================================
session_start();
session_destroy();
header('Location: /soulspet/login.php');
exit;
