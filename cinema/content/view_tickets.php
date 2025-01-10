<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

require 'config/koneksi.php';

$sql = 'SELECT t.id, t.ticket_number, t.seat, t.status, f.title, f.schedule, c.name AS customer_name
        FROM tickets t
        JOIN films f ON t.film_id = f.id
        JOIN customers c ON c.id = t.customer_id
        WHERE t.status = "sold"';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tickets = $stmt->fetchAll();
?>

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