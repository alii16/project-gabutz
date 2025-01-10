<?php
session_start();
require_once 'config/koneksi.php'; // Pastikan ada koneksi ke database

// Periksa apakah sesi user_id ada
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login jika sesi tidak ada
    header('Location: login.php');
    exit();
}

// Ambil data user setelah login
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, image_user FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['image_user'] = $user['image_user']; // Path foto user
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Cinema Ticket System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
</head>

<body class="bg-gray-100">
    <nav class="bg-indigo-700 shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Judul -->
                <div class="text-white text-2xl font-semibold">
                    <a href="index.php" class="hover:text-yellow-400 transition">Cinema Ticket System</a>
                </div>
                <!-- Tombol Toggle (Mobile) -->
                <button
                    class="inline-flex items-center p-2 ml-3 text-sm text-white rounded-lg md:hidden hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    data-collapse-toggle="navbar-default">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
                <!-- Daftar Menu -->
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul class="flex flex-col mt-4 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium">
                        <li>
                            <a href="index.php?page=view_films"
                                class="block py-2 px-4 text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition">View
                                Films</a>
                        </li>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li>
                                <a href="index.php?page=add_film"
                                    class="block py-2 px-4 text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition">Add
                                    Film</a>
                            </li>
                            <li>
                                <a href="index.php?page=view_all_booked_tickets"
                                    class="block py-2 px-4 text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition">View
                                    Booked Films</a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="index.php?page=view_user_booked_tickets"
                                    class="block py-2 px-4 text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition">My
                                    Tickets</a>
                            </li>
                        <?php endif; ?>
                        <!-- Menu Profil -->
                        <li>
                            <a href="index.php?page=profil"
                                class="block py-2 px-4 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">Profil</a>
                        </li>
                        <!-- Logout -->
                        <li>
                            <a href="logout.php"
                                class="block py-2 px-4 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>


    <div class="mt-6">
        <div class="content flex justify-center min-h-screen">
            <?php
            $page = @$_GET['page'];

            if (empty($page)) {
                include "content/home.php";
            } else {
                include "content/$page.php";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>
