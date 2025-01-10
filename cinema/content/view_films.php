<?php
// Memeriksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Memuat koneksi database
require 'config/koneksi.php';

// Query untuk mengambil daftar film yang tersedia
$stmt = $pdo->prepare('SELECT * FROM films');
$stmt->execute();
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);

// SQL query to count the total number of films using PDO
$query = "SELECT COUNT(*) AS total_films FROM films";
$stmt_count = $pdo->prepare($query);
$stmt_count->execute();
$row = $stmt_count->fetch(PDO::FETCH_ASSOC);
$total_films = $row['total_films'];

?>


<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Available Films</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-8 md:p-4 sm:p-2">
        <?php foreach ($films as $film): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Gambar -->
                <img class="w-full h-48 object-cover" src="<?= htmlspecialchars($film['image_url']) ?>"
                    alt="<?= htmlspecialchars($film['title']) ?>">
                <div class="p-4">
                    <!-- Judul -->
                    <h5 class="text-xl font-bold text-gray-900 dark:text-white">
                        <?= htmlspecialchars($film['title']) ?>
                    </h5>

                    <!-- Deskripsi -->
                    <p class="text-gray-700 text-sm mt-2 dark:text-gray-400">
                        <?= htmlspecialchars($film['description']) ?>
                    </p>

                    <!-- Rating -->
                    <div class="flex items-center mt-3">
                        <?php
                        $rating = intval($film['rating']);
                        for ($i = 0; $i < 5; $i++):
                            if ($i < $rating): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#E3A008" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 text-yellow-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                </svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                </svg>
                            <?php endif;
                        endfor; ?>
                    </div>

                    <!-- Jadwal -->
                    <p class="text-gray-600 text-sm mt-2 dark:text-gray-300">Schedule:
                        <?= htmlspecialchars($film['schedule']) ?>
                    </p>

                    <!-- Harga -->
                    <p class="text-gray-600 text-sm mt-2 dark:text-gray-300">Price: Rp
                        <?= number_format($film['price'], 0, ',', '.') ?>
                    </p>

                    <!-- Tombol Aksi -->
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <div class="flex mt-4 space-x-3">
                            <a href="index.php?page=edit_film&id=<?= $film['id'] ?>"
                                class="text-blue-500 hover:text-blue-700">Edit</a>
                            <a href="index.php?page=delete_film&id=<?= $film['id'] ?>" class="text-red-500 hover:text-red-700"
                                onclick="return confirm('Are you sure you want to delete this film?');">Delete</a>
                        </div>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] === 'customer'): ?>
                        <form method="POST" action="index.php?page=book_ticket" class="mt-4">
                            <input type="hidden" name="film_id" value="<?= htmlspecialchars($film['id']) ?>">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Book Ticket
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>



    <!-- Start coding here -->
    <div class="relative overflow-hidden bg-white rounded-b-lg mt-3 shadow-md dark:bg-gray-800">
        <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500">
                Total films available: <span class="font-semibold text-gray-900"><?= $total_films ?></span>
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