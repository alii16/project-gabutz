<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'koneksi.php';

$stmt = $pdo->prepare('
    SELECT t.id AS ticket_id, t.film_id, f.title, f.schedule, t.seat, t.status, f.price
    FROM tickets t
    JOIN films f ON t.film_id = f.id
    WHERE t.customer_id = ?
');
$stmt->execute([$_SESSION['user_id']]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booked Tickets</title>
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
        <h1 class="text-3xl font-bold mb-4 text-center">My Booked Tickets</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-lg shadow-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Film Title</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Schedule</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Seat</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Status</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Price</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td class="py-3 px-4"><?= htmlspecialchars($ticket['title']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($ticket['schedule']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($ticket['seat']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($ticket['status']) ?></td>
                            <td class="py-3 px-4">$<?= number_format($ticket['price'], 2) ?></td>
                            <td class="py-3 px-4">
                                <?php if ($ticket['status'] === 'sold'): ?>
                                    <a href="payment.php?film_id=<?= $ticket['film_id'] ?>&seat=<?= $ticket['seat'] ?>" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Pay Now</a>
                                    <form method="POST" action="cancel_ticket.php" class="inline-block">
                                        <input type="hidden" name="ticket_id" value="<?= $ticket['ticket_id'] ?>">
                                        <button type="submit"
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <span class="px-4 py-2 text-gray-500">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
