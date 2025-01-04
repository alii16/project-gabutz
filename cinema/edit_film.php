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

// Mendapatkan data film berdasarkan ID
$stmt = $pdo->prepare('SELECT * FROM films WHERE id = ?');
$stmt->execute([$film_id]);
$film = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$film) {
    header('Location: view_films.php');
    exit;
}

// Memproses data dari form ketika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $rating = $_POST['rating'];
    $schedule = $_POST['schedule'];

    // Query untuk mengupdate data film
    $stmt = $pdo->prepare('UPDATE films SET title = ?, duration = ?, rating = ?, schedule = ? WHERE id = ?');
    if ($stmt->execute([$title, $duration, $rating, $schedule, $film_id])) {
        header('Location: view_films.php');
        exit;
    } else {
        $error = 'Failed to update film';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Film</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Edit Film</h1>
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-lg mx-auto">
            <form method="POST" class="p-6">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($film['title']) ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Duration (mins)</label>
                    <input type="number" name="duration" value="<?= htmlspecialchars($film['duration']) ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                    <input type="text" name="rating" value="<?= htmlspecialchars($film['rating']) ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Schedule</label>
                    <input type="text" name="schedule" value="<?= htmlspecialchars($film['schedule']) ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg transition duration-300 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Update
                    Film</button>
                <?php if (!empty($error)): ?>
                    <p class="text-red-500 mt-2"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>

</html>