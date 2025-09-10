<?php
// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "websiteyp";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil jumlah anak yatim/piatu
$res_anak = $conn->query("SELECT COUNT(*) AS total FROM anak_asuh");
$jumlah_anak = $res_anak->fetch_assoc()['total'] ?? 0;

// Ambil jumlah UMKM
$res_umkm = $conn->query("SELECT COUNT(*) AS total FROM umkm");
$jumlah_umkm = $res_umkm->fetch_assoc()['total'] ?? 0;

// Routing
$page = $_GET['page'] ?? 'home';
$allowed_pages = [
    'home', 
    'pegawai', 
    'pegawai_form', 
    'pegawai_detail', 
    'keluarga_asuh', 
    'anak_asuh',          // tambahkan halaman anak_asuh
    'penerima_bantuan', 
    'ay_ayp',
    'pensiunan',
    'umkm', 
    'umkm_form',
    'umkm_detail'
];

// Jika page tidak ada atau file tidak ditemukan, default ke home
if (!in_array($page, $allowed_pages) || !file_exists($page . '.php')) {
    $page = 'home';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Manajemen Internal YAYASAN PUSAKA KAI</title>
    <style>
        /* --- NAV DROPDOWN --- */
        nav {
            display: flex;
            justify-content: center;
            gap: 30px;
            background-color: #ff8c00;
            padding: 12px 0;
            position: relative;
        }
        nav a, .dropbtn {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 15px;
            display: inline-block;
        }
        nav a:hover, .dropdown:hover .dropbtn { text-decoration: underline; }
        .dropdown { position: relative; display: inline-block; }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 180px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
        }
        .dropdown-content a {
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover { background-color: #f0f0f0; }
        .dropdown:hover .dropdown-content { display: block; }

        /* Responsive */
        @media (max-width: 600px){
            nav { flex-direction: column; gap: 10px; }
            .dropdown-content { position: static; box-shadow: none; }
        }

        /* --- GENERAL STYLES --- */
        * { box-sizing: border-box; margin:0; padding:0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f0f2f5; color: #333; }

        header {
            background-color: #f0f2f5;
            color: #2d2a70;
            padding: 20px;
        }
        .header-container { display: flex; justify-content: center; align-items: center; position: relative; }
        .header-title { font-size: 1.8em; text-align: center; }
        .header-logo { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); }
        .header-logo img { height: 60px; width: auto; }
        @media(max-width: 600px){
            .header-container { flex-direction: column; }
            .header-logo { position: static; margin-top: 10px; }
            .header-logo img { height: 50px; }
        }

        .dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; padding: 20px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .table-container { margin: 20px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        th, td { padding: 14px 20px; text-align: left; }
        th { background-color: #4e73df; color: white; font-weight: 600; }
        tr:nth-child(even) { background-color: #f8f9fc; }
        tr:hover { background-color: #d1d9ff; transition: 0.2s; }
    </style>
</head>
<body>

<header>
    <div class="header-container">
        <div class="header-title">
            <b>Website Manajemen Internal<br>YAYASAN PUSAKA KAI</b>
        </div>
        <div class="header-logo">
            <img src="foto/logoyp.png" alt="Logo Instansi">
        </div>
    </div>
</header>

<nav>
    <a href="index.php?page=home">Home</a>
    <a href="index.php?page=pegawai">Data Pegawai</a>
    <a href="index.php?page=keluarga_asuh">Data Keluarga Asuh</a>
    <a href="index.php?page=anak_asuh">Data Anak Asuh</a>

    <div class="dropdown">
        <a href="index.php?page=penerima_bantuan" class="dropbtn">Bantuan ▼</a>
        <div class="dropdown-content">
            <a href="index.php?page=ay_ayp">Yatim & Yatim Piatu</a>
            <a href="index.php?page=pensiunan">Pensiunan</a>
        </div>
    </div>

    <a href="index.php?page=umkm">UMKM</a>
</nav>

<main>
    <?php include $page . '.php'; ?>
</main>

</body>
</html>
