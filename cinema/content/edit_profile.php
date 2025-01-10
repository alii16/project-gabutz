<?php
require_once 'config/koneksi.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil data user dari database berdasarkan user_id
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, phone, image_user FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data user tidak ditemukan, redirect ke login
if (!$user) {
    header('Location: login.php');
    exit;
}

// Proses update data user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $image_user = $user['image_user']; // Default foto profil

    // Periksa jika ada file yang diunggah
    if (isset($_FILES['image_user']) && $_FILES['image_user']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = uniqid() . '-' . basename($_FILES['image_user']['name']);
        $file_path = $upload_dir . $file_name;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($_FILES['image_user']['tmp_name'], $file_path)) {
            $image_user = $file_path; // Update path foto profil
        }
    }

    // Update data ke database
    $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, phone = :phone, image_user = :image_user WHERE id = :id");
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'image_user' => $image_user,
        'id' => $user_id
    ]);

    // Redirect kembali ke halaman profil
    header('Location: index.php?page=profil');
    exit;
}
?>

<div class="max-w-4xl mx-auto my-10 p-8 bg-white rounded-xl shadow-lg">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-indigo-600">Edit Profil</h1>
        <p class="text-gray-500">Perbarui informasi profil Anda di bawah ini.</p>
    </div>

    <!-- Formulir Edit Profil -->
    <form action="index.php?page=edit_profile" method="POST" enctype="multipart/form-data" class="space-y-6">
        <!-- Username -->
        <div>
            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required>
        </div>

        <!-- Nomor Telepon -->
        <div>
            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Telepon</label>
            <input type="number" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                required>
        </div>

        <!-- Foto Profil -->
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image_user">Upload
                file</label>
            <input
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                aria-describedby="file_input_help" id="file_input" type="file" name="image_user">
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG, JPG or JPEG (MAX.
                800x400px).</p>

            <div class="flex items-center space-x-4">
                <!-- Input File -->

                <!-- Tampilkan Foto Saat Ini -->
                <?php if (!empty($user['image_user'])): ?>
                    <img src="<?= htmlspecialchars($user['image_user']) ?>" alt="Foto Profil"
                        class="w-20 h-20 rounded-full shadow-lg border-2 border-indigo-500">
                <?php endif; ?>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Simpan
            </button>
        </div>
    </form>
</div>