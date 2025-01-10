<?php
require_once 'config/koneksi.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil data user dari database berdasarkan user_id
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, phone, image_user FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data user tidak ditemukan, redirect ke login
if (!$user) {
    header('Location: login.php');
    exit;
}
?>

<div class="max-w-4xl mx-auto my-10 p-6 bg-white rounded-lg shadow-md">
        <!-- Profil Header -->
        <div class="flex flex-col items-center space-y-4">
            <!-- Foto Profil -->
            <img src="<?= !empty($user['image_user']) ? htmlspecialchars($user['image_user']) : 'default-avatar.png' ?>" 
                 alt="Foto User" 
                 class="w-32 h-32 rounded-full border-4 border-indigo-500 object-cover">

            <!-- Nama User -->
            <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($user['username']) ?></h1>
            <p class="text-gray-600"><?= htmlspecialchars($user['email']) ?></p>
            <p class="text-gray-600"><?= htmlspecialchars($user['phone']) ?></p>
        </div>

        <!-- Tombol Edit Profil -->
        <div class="mt-6 flex justify-center">
            <a href="index.php?page=edit_profile"
               class="px-6 py-2 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 transition">
                Edit Profil
            </a>
        </div>

        <!-- Informasi Tambahan -->
        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-700">Informasi Tambahan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <!-- Detail -->
                <div class="p-4 border rounded-lg">
                    <h3 class="text-indigo-600 font-medium">Username</h3>
                    <p><?= htmlspecialchars($user['username']) ?></p>
                </div>
                <div class="p-4 border rounded-lg">
                    <h3 class="text-indigo-600 font-medium">Email</h3>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <div class="p-4 border rounded-lg">
                    <h3 class="text-indigo-600 font-medium">Nomor Telepon</h3>
                    <p><?= htmlspecialchars($user['phone']) ?></p>
                </div>
            </div>
        </div>
    </div>
