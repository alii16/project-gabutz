<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $rating = $_POST['rating'];
    $schedule = $_POST['schedule'];

    $stmt = $pdo->prepare('INSERT INTO films (title, duration, rating, schedule) VALUES (?, ?, ?, ?)');
    if ($stmt->execute([$title, $duration, $rating, $schedule])) {
        $success = 'New film added successfully';
    } else {
        $error = 'Failed to add new film';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Film</title>
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

    <div class="flex items-center justify-center h-screen mt-6">
        <div class="bg-white bg-opacity-90 p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-bold mb-6 text-center text-blue-600">Add New Film</h1>
            <?php if (!empty($success)): ?>
                <p class="text-green-500 mb-4"><?= htmlspecialchars($success) ?></p>
            <?php elseif (!empty($error)): ?>
                <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                    <input type="text" name="title"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Duration (in minutes)</label>
                    <input type="number" name="duration"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                    <input type="text" name="rating"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Schedule</label>
                    <input type="datetime-local" name="schedule"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg transition duration-300 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Add
                    Film</button>
            </form>
        </div>
    </div>
</body>

</html>