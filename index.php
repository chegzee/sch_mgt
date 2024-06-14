<?php
if (session_status() == PHP_SESSION_NONE) {
  echo session_start();
}

require_once '../app/require.php';