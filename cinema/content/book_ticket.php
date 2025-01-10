<?php

// Memeriksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Memuat koneksi database
require 'config/koneksi.php';

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
            header('Location: index.php?page=view_user_booked_tickets');
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
$bookedSeatsStmt = $pdo->prepare('SELECT seat FROM tickets WHERE status IN ("paid", "sold") AND film_id = ?');
$bookedSeatsStmt->execute([$selected_film_id]);
$bookedSeats = $bookedSeatsStmt->fetchAll(PDO::FETCH_COLUMN, 0);
?>

<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Book Tickets</h1>
    <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-md mx-auto">
        <form method="POST" action="index.php?page=book_ticket" class="p-6">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Select Film</label>
                <select name="film_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                    onchange="this.form.submit()">
                    <?php foreach ($films as $film): ?>
                        <option value="<?= $film['id'] ?>" <?= $selected_film_id == $film['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($film['title']) ?></option>
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
                            $disabled = in_array($seat, $bookedSeats) ? 'disabled' : '';
                            echo "<option value=\"$seat\" $disabled>$seat" . ($disabled ? ' (Booked)' : '') . "</option>";
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
