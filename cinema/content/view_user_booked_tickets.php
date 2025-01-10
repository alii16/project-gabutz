<?php

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config/koneksi.php';

// Ensure the necessary variables are defined
$user_id = $_SESSION['user_id'];  // Assuming the user ID is stored in the session
$per_page = 10;  // Set the number of tickets per page (for example)
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;  // Ensure the page is at least 1
$start = ($page - 1) * $per_page;  // Calculate the starting point for the LIMIT

// Query to count total tickets booked by the user
$stmt = $pdo->prepare('
    SELECT COUNT(*) AS ticket_count
    FROM tickets t
    WHERE t.customer_id = :user_id
');
$stmt->execute([':user_id' => intval($user_id)]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$ticket_count = $row['ticket_count']; // Total number of booked tickets

// Query to fetch paginated ticket data booked by the user
$stmt = $pdo->prepare('
    SELECT t.id AS ticket_id, t.film_id, f.title, f.schedule, t.seat, t.status, f.price, f.image_url
    FROM tickets t
    JOIN films f ON t.film_id = f.id
    WHERE t.customer_id = :user_id
    LIMIT :start, :per_page
');

// Bind parameters explicitly
$stmt->bindValue(':user_id', intval($user_id), PDO::PARAM_INT);
$stmt->bindValue(':start', intval($start), PDO::PARAM_INT);
$stmt->bindValue(':per_page', intval($per_page), PDO::PARAM_INT);

// Execute the query
$stmt->execute();

// Fetch all tickets for the current page
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate the total number of pages
$total_pages = ceil($ticket_count / $per_page);
?>


<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4 text-center">My Tickets</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-8 md:p-4 sm:p-2">
    <?php foreach ($tickets as $ticket): ?>
        <div class="bg-white border rounded-lg shadow-lg overflow-hidden">
            <img src="<?= htmlspecialchars($ticket['image_url']) ?>" alt="<?= htmlspecialchars($ticket['title']) ?>" class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($ticket['title']) ?></h3>
                <p class="text-gray-600 text-sm">Schedule: <?= htmlspecialchars($ticket['schedule']) ?></p>
                <p class="text-gray-600 text-sm">Seat: <?= htmlspecialchars($ticket['seat']) ?></p>
                <p class="text-gray-600 text-sm">Status: <?= htmlspecialchars($ticket['status']) ?></p>
                <p class="text-gray-800 font-bold text-xl">Rp <?= number_format($ticket['price'], 0, ',', '.') ?></p>
                <div class="mt-4">
                    <?php if ($ticket['status'] === 'sold'): ?>
                        <a href="index.php?page=payment&film_id=<?= $ticket['film_id'] ?>&seat=<?= $ticket['seat'] ?>"
                            class="block text-center py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Pay Now</a>
                        <form method="POST" action="index.php?page=cancel_ticket" class="inline-block w-full mt-2">
                            <input type="hidden" name="ticket_id" value="<?= $ticket['ticket_id'] ?>">
                            <button type="submit" class="w-full py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                Cancel
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="block text-center py-2 text-gray-500">N/A</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>



    <!-- Start coding here -->
    <div class="relative overflow-hidden bg-white rounded-b-lg mt-2 shadow-md dark:bg-gray-800">
        <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500">
                Total tickets booked: <span class="font-semibold text-gray-900"><?= $ticket_count ?></span>
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