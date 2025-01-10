<?php
// Memeriksa apakah pengguna telah login dan memiliki peran admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Memuat koneksi database
require 'config/koneksi.php';

// Memeriksa apakah ID film ada di URL
if (!isset($_GET['id'])) {
    header('Location: index.php?page=view_films');
    exit;
}

$film_id = $_GET['id'];

// Mendapatkan data film berdasarkan ID
$stmt = $pdo->prepare('SELECT * FROM films WHERE id = ?');
$stmt->execute([$film_id]);
$film = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$film) {
    header('Location: index.php?page=view_films');
    exit;
}

// Memproses data dari form ketika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $rating = $_POST['rating'];
    $schedule = $_POST['schedule'];
    $description = $_POST['description'];  // Menambahkan deskripsi
    $image_url = $film['image_url']; // Tetap menggunakan gambar lama jika tidak ada gambar baru yang diupload

    // Memproses upload gambar baru jika ada
    if (!empty($_FILES['image_url']['name'])) {
        $image_tmp = $_FILES['image_url']['tmp_name'];
        $image_name = $_FILES['image_url']['name'];
        $image_path = 'uploads/' . $image_name;

        // Pindahkan gambar ke folder uploads
        if (move_uploaded_file($image_tmp, $image_path)) {
            $image_url = $image_path;
        } else {
            $error = 'Failed to upload image';
        }
    }

    // Query untuk mengupdate data film
    $stmt = $pdo->prepare('UPDATE films SET title = ?, duration = ?, rating = ?, schedule = ?, description = ?, image_url = ? WHERE id = ?');
    if ($stmt->execute([$title, $duration, $rating, $schedule, $description, $image_url, $film_id])) {
        header('Location: index.php?page=view_films');
        exit;
    } else {
        $error = 'Failed to update film';
    }
}
?>

<div class="container mx-auto py-8">
    <h1 class="text-4xl font-extrabold mb-6 text-center text-indigo-600">Edit Film</h1>
    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 shadow-lg rounded-lg overflow-hidden max-w-lg mx-auto">
        <form method="POST" class="p-6 bg-white rounded-b-lg" enctype="multipart/form-data">
            <!-- Title -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">🎬 Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($film['title']) ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-indigo-500 transition duration-300"
                    required>
            </div>

            <!-- Duration -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">⏱️ Duration (mins)</label>
                <input type="number" name="duration" value="<?= htmlspecialchars($film['duration']) ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-indigo-500 transition duration-300"
                    required>
            </div>

            <!-- Rating -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">⭐ Rating</label>
                <input type="text" name="rating" value="<?= htmlspecialchars($film['rating']) ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-indigo-500 transition duration-300"
                    required>
            </div>

            <!-- Schedule -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">📅 Schedule</label>
                <input type="text" name="schedule" value="<?= htmlspecialchars($film['schedule']) ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-indigo-500 transition duration-300"
                    required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">📝 Description</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-indigo-500 transition duration-300"
                    required><?= htmlspecialchars($film['description']) ?></textarea>
            </div>

            <!-- Image Input (untuk mengupdate foto) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">🎥 Update Image</label>
                <input type="file" name="image_url" accept="image/*"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-indigo-500 transition duration-300">
                <small class="text-gray-500">Kosongkan jika tidak ingin mengganti gambar</small>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg transition duration-300 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-lg transform hover:scale-105">
                Update Film
            </button>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <p class="text-red-500 mt-4 text-center font-semibold"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>
