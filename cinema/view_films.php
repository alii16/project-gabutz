<?php
session_start();

// Memeriksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Memuat koneksi database
require 'koneksi.php';

// Query untuk mengambil daftar film yang tersedia
$stmt = $pdo->prepare('SELECT * FROM films');
$stmt->execute();
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Films</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .navbar a:hover {
            background-color: #f97316;
            /* Orange 600 */
        }
    </style>
</head>

<body class="bg-gray-100">
    <nav class="bg-indigo-700 shadow-lg">
        <div class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div>
                    <a href="index.php" class="text-xl font-bold text-white lg:text-2xl">Cinema Ticket System</a>
                </div>
                <div class="flex space-x-4">
                    <a href="view_films.php"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">View Films</a>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="add_film.php" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Add
                            Film</a>
                        <a href="view_all_booked_tickets.php"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">View Booked Films</a>
                    <?php else: ?>
                        <a href="view_user_booked_tickets.php"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">My Tickets</a>
                    <?php endif; ?>
                    <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Available Films</h1>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200 text-gray-600 uppercase text-md leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-left">Title</th>
                            <th class="py-3 px-6 text-left">Duration (mins)</th>
                            <th class="py-3 px-6 text-left">Rating</th>
                            <th class="py-3 px-6 text-left">Schedule</th>
                            <th class="py-3 px-6 text-left">Price ($)</th>
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <th class="py-3 px-6 text-left">Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-md font-light">
                        <?php foreach ($films as $film): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left"><?= htmlspecialchars($film['title']) ?></td>
                                <td class="py-3 px-6 text-left"><?= htmlspecialchars($film['duration']) ?></td>
                                <td class="py-3 px-6 text-left"><?= htmlspecialchars($film['rating']) ?></td>
                                <td class="py-3 px-6 text-left"><?= htmlspecialchars($film['schedule']) ?></td>
                                <td class="py-3 px-6 text-left"><?= htmlspecialchars($film['price']) ?></td>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <td class="py-3 px-6 text-left">
                                        <a href="edit_film.php?id=<?= $film['id'] ?>"
                                            class="text-blue-500 hover:text-blue-700">Edit</a> |
                                        <a href="delete_film.php?id=<?= $film['id'] ?>" class="text-red-500 hover:text-red-700"
                                            onclick="return confirm('Are you sure you want to delete this film?');">Delete</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="justify-center flex">
        <?php if ($_SESSION['role'] === 'customer'): ?>
            <form method="POST" action="book_ticket.php">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Book
                    Ticket</button>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>