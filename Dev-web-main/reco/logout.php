<?php

session_start();

if (isset($_SESSION['user'])) {
    unset($_SESSION);
    session_destroy();
}

header('Location:index.php');