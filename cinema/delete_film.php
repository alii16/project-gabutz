<?php
session_start();

// Memeriksa apakah pengguna telah login dan memiliki peran admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Memuat koneksi database
require 'koneksi.php';

// Memeriksa apakah ID film ada di URL
if (!isset($_GET['id'])) {
    header('Location: view_films.php');
    exit;
}

$film_id = $_GET['id'];

try {
    // Mulai transaksi
    $pdo->beginTransaction();

    // Hapus tiket terkait
    $stmt = $pdo->prepare('DELETE FROM tickets WHERE film_id = ?');
    $stmt->execute([$film_id]);

    // Hapus film
    $stmt = $pdo->prepare('DELETE FROM films WHERE id = ?');
    $stmt->execute([$film_id]);

    // Komit transaksi
    $pdo->commit();

    header('Location: view_films.php');
    exit;
} catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    $pdo->rollBack();
    $error = 'Failed to delete film: ' . $e->getMessage();
    header('Location: view_films.php?error=' . urlencode($error));
    exit;
}
