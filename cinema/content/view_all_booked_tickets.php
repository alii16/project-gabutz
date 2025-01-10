<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

require 'config/koneksi.php';

$stmt = $pdo->prepare('
    SELECT t.id AS ticket_id, f.title, f.schedule, t.name AS customer_name, t.seat, t.status
    FROM tickets t
    JOIN films f ON t.film_id = f.id
    WHERE t.status = "paid"  -- Kondisi untuk tiket yang sudah dibayar
');
$stmt->execute();
$booked_films = $stmt->fetchAll(PDO::FETCH_ASSOC);

$countStmt = $pdo->prepare('SELECT COUNT(*) AS total_tickets FROM tickets WHERE status = "paid"');
$countStmt->execute();
$total_tickets = $countStmt->fetch(PDO::FETCH_ASSOC)['total_tickets'];
?>

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
    <!-- Start coding here -->
    <div class="relative overflow-hidden bg-white rounded-b-lg mt-3 shadow-md dark:bg-gray-800">
        <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500">
                Total booked tickets: <span class="font-semibold text-gray-900"><?= $total_tickets ?></span>
            </span>
            <ul class="inline-flex items-stretch -space-x-px">
                <li>
                    <a href="#"
                        class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <span class="sr-only">Previous</span>
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                </li>
                <li>
                    <a href="#" aria-current="page"
                        class="z-10 flex items-center justify-center px-3 py-2 text-sm leading-tight border text-primary-600 bg-primary-50 border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <span class="sr-only">Next</span>
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>