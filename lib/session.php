<?php
session_start();

// Cek dan hapus pesan login berhasil
if (isset($_SESSION['new_login'])) {
    $message = $_SESSION['new_login'];
    unset($_SESSION['new_login']);
} else {
    $message = '';
}
