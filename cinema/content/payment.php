<?php

// Memeriksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Memuat koneksi database
require 'config/koneksi.php';

// Memeriksa apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari form
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
    $film_id = isset($_POST['film_id']) ? $_POST['film_id'] : '';
    $seat = isset($_POST['seat']) ? $_POST['seat'] : '';
    $ticket_price = isset($_POST['ticket_price']) ? $_POST['ticket_price'] : '';
    $user_id = $_SESSION['user_id']; // Pastikan user_id ada di dalam $_SESSION

    // Menyimpan data pembayaran ke database
    $stmt = $pdo->prepare('UPDATE tickets SET name = ?, payment_method = ?, status = "paid" WHERE film_id = ? AND seat = ? AND status = "sold" AND customer_id = ?');
    $stmt->execute([$name, $payment_method, $film_id, $seat, $user_id]);

    // Redirect ke halaman tiket pengguna dengan pesan sukses
    $_SESSION['message'] = 'Payment successful!';
    header('Location: index.php?page=view_user_booked_tickets');
    exit;
}

// Mengambil data tiket yang perlu dibayar
$film_id = $_GET['film_id'];
$seat = $_GET['seat'];

$stmt = $pdo->prepare('SELECT * FROM tickets WHERE film_id = ? AND seat = ? AND status = "sold" AND customer_id = ?');
$stmt->execute([$film_id, $seat, $_SESSION['user_id']]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    // Jika tiket tidak ditemukan, redirect ke halaman tiket pengguna dengan pesan error
    $_SESSION['message'] = 'Ticket not found or already paid!';
    header('Location: index.php?page=view_user_booked_tickets');
    exit;
}

// Ambil informasi film untuk ditampilkan kepada pengguna
$stmt = $pdo->prepare('SELECT * FROM films WHERE id = ?');
$stmt->execute([$film_id]);
$film = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil informasi kursi yang dipilih
$seat = $ticket['seat'];

// Ambil harga tiket dari database atau sesuaikan dengan logika bisnis Anda
$ticket_price = 10; // Misalnya, harga tiket adalah $10
?>

<div class="container mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-md mx-auto">
            <form method="POST" class="p-6">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                    <input type="text" id="name" name="name"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="payment_method" class="block text-gray-700 text-sm font-bold mb-2">Payment
                        Method</label>
                    <select id="payment_method" name="payment_method"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="paypal">PayPal</option>
                        <!-- Tambahkan pilihan metode pembayaran lain sesuai kebutuhan -->
                    </select>
                </div>

                <input type="hidden" name="film_id" value="<?= htmlspecialchars($film_id) ?>">
                <input type="hidden" name="seat" value="<?= htmlspecialchars($seat) ?>">
                <input type="hidden" name="ticket_price" value="<?= htmlspecialchars($ticket_price) ?>">

                <button type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg transition duration-300 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Pay
                    Now</button>
                <?php if (!empty($error)): ?>
                    <p class="text-red-500 mt-2"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
