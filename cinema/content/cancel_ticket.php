<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ticket_id'])) {
        $ticket_id = $_POST['ticket_id'];
        $user_id = $_SESSION['user_id'];

        // Memastikan bahwa tiket tersebut milik pengguna yang sedang login
        $stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = ? AND customer_id = ?');
        $stmt->execute([$ticket_id, $user_id]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ticket) {
            // Menghapus tiket
            $stmt = $pdo->prepare('DELETE FROM tickets WHERE id = ?');
            $stmt->execute([$ticket_id]);

            $_SESSION['message'] = "Ticket successfully canceled.";
        } else {
            $_SESSION['error'] = "Ticket not found or you do not have permission to cancel this ticket.";
        }
    } else {
        $_SESSION['error'] = "Invalid request.";
    }

    header('Location: index.php?page=view_user_booked_tickets');
    exit;
} else {
    header('Location: index.php');
    exit;
}
