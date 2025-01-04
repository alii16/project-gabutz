<?php
session_start();

// Memeriksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Memuat koneksi database
require 'koneksi.php';

// Memeriksa role pengguna untuk memastikan hanya pelanggan yang dapat mengakses
if ($_SESSION['role'] !== 'customer') {
    // Redirect ke halaman yang sesuai jika bukan pelanggan
    header('Location: index.php'); // Ganti dengan halaman yang sesuai
    exit;
}

// Handle form submission untuk memesan tiket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['film_id']) && isset($_POST['seat'])) {
    // Memproses data dari form
    $film_id = $_POST['film_id'];
    $seat = $_POST['seat'];

    // Memastikan kursi belum dipesan
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM tickets WHERE film_id = ? AND seat = ? AND status = "sold"');
    $stmt->execute([$film_id, $seat]);
    $seat_count = $stmt->fetchColumn();

    if ($seat_count == 0) {
        // Memesan tiket
        $stmt = $pdo->prepare('INSERT INTO tickets (film_id, seat, status, customer_id) VALUES (?, ?, "sold", ?)');
        if ($stmt->execute([$film_id, $seat, $_SESSION['user_id']])) {
            // Redirect ke halaman pembayaran setelah berhasil memesan tiket
            header('Location: view_user_booked_tickets.php');
            exit;
        } else {
            $error = 'Failed to book ticket';
        }
    } else {
        $error = 'Seat already booked. Please choose another seat.';
    }
}

// Query untuk mengambil daftar film yang tersedia
$stmt = $pdo->prepare('SELECT * FROM films');
$stmt->execute();
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengambil daftar kursi yang sudah dipesan untuk film yang dipilih
$selected_film_id = isset($_POST['film_id']) ? $_POST['film_id'] : $films[0]['id'];
$bookedSeatsStmt = $pdo->prepare('SELECT seat FROM tickets WHERE status = "sold" AND film_id = ?');
$bookedSeatsStmt->execute([$selected_film_id]);
$bookedSeats = $bookedSeatsStmt->fetchAll(PDO::FETCH_COLUMN, 0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tickets</title>
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
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Book Tickets</h1>
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-md mx-auto">
            <form method="POST" action="book_ticket.php" class="p-6">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Select Film</label>
                    <select name="film_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" onchange="this.form.submit()">
                        <?php foreach ($films as $film): ?>
                            <option value="<?= $film['id'] ?>" <?= $selected_film_id == $film['id'] ? 'selected' : '' ?>><?= htmlspecialchars($film['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Select Seat</label>
                    <select name="seat" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <?php
                        $rows = range('A', 'E');
                        $cols = range(1, 5);
                        foreach ($rows as $row) {
                            foreach ($cols as $col) {
                                $seat = $row . $col;
                                echo "<option value=\"$seat\" " . (in_array($seat, $bookedSeats) ? 'disabled' : '') . ">$seat</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg transition duration-300 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Book
                    Ticket</button>
                <?php if (!empty($error)): ?>
                    <p class="text-red-500 mt-2"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>

</html>
