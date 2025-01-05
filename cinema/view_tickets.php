<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

require 'koneksi.php';

$sql = 'SELECT t.id, t.ticket_number, t.seat, t.status, f.title, f.schedule, c.name AS customer_name
        FROM tickets t
        JOIN films f ON t.film_id = f.id
        JOIN customers c ON c.id = t.customer_id
        WHERE t.status = "sold"';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booked Tickets</title>
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
        <h1 class="text-2xl font-bold mb-4">Booked Tickets</h1>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200">Ticket Number</th>
                    <th class="py-2 px-4 border-b border-gray-200">Seat</th>
                    <th class="py-2 px-4 border-b border-gray-200">Film Title</th>
                    <th class="py-2 px-4 border-b border-gray-200">Schedule</th>
                    <th class="py-2 px-4 border-b border-gray-200">Customer Name</th>
                    <th class="py-2 px-4 border-b border-gray-200">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($ticket['ticket_number']) ?>
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($ticket['seat']) ?></td>
                        <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($ticket['title']) ?></td>
                        <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($ticket['schedule']) ?></td>
                        <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($ticket['customer_name']) ?>
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($ticket['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
