<?php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

require 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $rating = $_POST['rating'];
    $schedule = $_POST['schedule'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Proses upload gambar
    $image_url = '';  // Default value jika tidak ada gambar yang diupload
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image_url']['tmp_name'];
        $fileName = $_FILES['image_url']['name'];
        $fileSize = $_FILES['image_url']['size'];
        $fileType = $_FILES['image_url']['type'];

        // Tentukan folder tempat menyimpan file
        $uploadPath = 'uploads/' . $fileName;

        // Pindahkan file ke folder 'uploads/'
        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            // Jika berhasil upload, simpan nama file
            $image_url = $uploadPath;
        } else {
            $error = 'Failed to upload image.';
        }
    } else {
        $error = 'No image uploaded or there was an error uploading the image.';
    }

    // Simpan data film ke database
    if (empty($error)) {
        $stmt = $pdo->prepare('INSERT INTO films (title, duration, rating, schedule, price, description, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)');
        if ($stmt->execute([$title, $duration, $rating, $schedule, $price, $description, $image_url])) {
            $success = 'New film added successfully';
        } else {
            $error = 'Failed to add new film';
        }
    }
}
?>

<div class="max-w-4xl mx-auto my-10 p-8 bg-white rounded-xl shadow-lg">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-indigo-600">Add New Film</h1>
    </div>
    <?php if (!empty($success)): ?>
        <p class="text-green-500 mb-4"><?= htmlspecialchars($success) ?></p>
    <?php elseif (!empty($error)): ?>
        <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-6" enctype="multipart/form-data">
        <!-- Title Input -->
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
            <input type="text" name="title"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required>
        </div>
        <!-- Duration Input -->
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Duration (in minutes)</label>
            <input type="number" name="duration"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required>
        </div>
        <!-- Rating Input -->
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rating</label>
            <input type="text" name="rating"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required>
        </div>
        <!-- Schedule Input -->
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Schedule</label>
            <input type="datetime-local" name="schedule"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required>
        </div>
        <!-- Price Input -->
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
            <input type="number" name="price" step="0.01"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required>
        </div>
        <!-- Description Input -->
        <div class="sm:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
            <textarea name="description"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required></textarea>
        </div>
        <!-- Image Input -->
        <div class="sm:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
            <input type="file" name="image_url" accept="image/*"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required>
        </div>
        <button type="submit"
            class="w-full bg-indigo-500 text-white px-5 py-3 rounded-lg transition duration-300 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:col-span-2">Add
            Film</button>
    </form>
</div>