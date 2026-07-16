<?php

function requireLogin(): void {
    if (!isset($_SESSION["user"])) {
        header("Location: login.php");
        exit();
    }
}
