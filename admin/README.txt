Skrip PHP di bawah ini harus diletakkan di bagian atas setiap halaman /admin/. Buat ngecek apakah user admin atau bukan.

<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}
?>
