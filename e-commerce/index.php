<?php

session_start();
include_once('db_connect.php');
$database = new database();

// if(isset($_SESSION['login'])){
//     header('location:index.php');
// }

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($database->login($username, $password)) {
    // Ambil nama pengguna dari database setelah login berhasil
    $query = "SELECT nama, username FROM pendaftaran WHERE username = ?";
    $stmt = $database->conn->prepare($query); // Asumsikan $database->conn adalah koneksi mysqli
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
      // Simpan nama ke dalam sesi
      $_SESSION['nama'] = $row['nama'];
      $_SESSION['username'] = $row['user'];
    }

    // Redirect ke halaman index
    header('location:index.php');
    exit; // Menghentikan eksekusi setelah redirect
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/preline@1.3.0/dist/preline.min.css" rel="stylesheet">

  <title>Home</title>
</head>

<body class="p-6">


  <?php include 'components/navbar.php'; ?>

  <?php include 'components/hero.php'; ?>

  <?php include 'components/footer.php'; ?>

  <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <!-- Flowbite JS -->
  <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
  <script src="https://kit.fontawesome.com/0016cb88ab.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/preline@1.3.0/dist/preline.min.js"></script>


</body>

</html>