<div class="container">
    <h1 class="text-5xl font-extrabold text-center text-indigo-800 mb-8">Welcome to Cinema Ticket System</h1>
    <div class="flex justify-center mb-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-xl text-gray-700">Hello, <span
                    class="font-semibold text-indigo-700"><?= htmlspecialchars($_SESSION['username']) ?></span></p>

            <!-- Menampilkan foto pengguna -->
            <div class="mt-4 flex justify-center">
                <img src="<?= htmlspecialchars($_SESSION['image_user']) ?>" alt="User Photo"
                    class="rounded-full w- h-40 object-cover">
            </div>
        </div>
    </div>

    <div class="flex justify-center">
        <a href="index.php?page=view_films"
            class="px-5 py-3 text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 shadow-lg transition">Explore
            Films</a>
    </div>
</div>