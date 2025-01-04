<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

require 'koneksi.php';

$stmt = $pdo->prepare('
    SELECT t.id AS ticket_id, f.title, f.schedule, t.name AS customer_name, t.seat, t.status
    FROM tickets t
    JOIN films f ON t.film_id = f.id
    WHERE t.status = "paid"  -- Kondisi untuk tiket yang sudah dibayar
');
$stmt->execute();
$booked_films = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booked Films</title>
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
                    <a href="view_films.php" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">View Films</a>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="add_film.php" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Add Film</a>
                        <a href="view_all_booked_tickets.php" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">View Booked Films</a>
                    <?php endif; ?>
                    <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-4 text-center">Booked Films</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-lg shadow-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Film Title</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Schedule</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Customer Name</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Seat</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($booked_films as $film): ?>
                        <tr>
                            <td class="py-3 px-4"><?= htmlspecialchars($film['title']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($film['schedule']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($film['customer_name']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($film['seat']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($film['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
