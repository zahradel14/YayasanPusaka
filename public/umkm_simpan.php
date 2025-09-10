<?php
include __DIR__ . "/../backend/koneksi.php";

// pastikan method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input teks
    $id_keluarga      = mysqli_real_escape_string($koneksi, $_POST['id_keluarga']);
    $nama_usaha       = mysqli_real_escape_string($koneksi, $_POST['nama_usaha']);
    $jenis_usaha      = mysqli_real_escape_string($koneksi, $_POST['jenis_usaha']);
    $alamat_usaha     = mysqli_real_escape_string($koneksi, $_POST['alamat_usaha']);
    $produk           = mysqli_real_escape_string($koneksi, $_POST['produk']);
    $tahun_mulai      = mysqli_real_escape_string($koneksi, $_POST['tahun_mulai']);
    $nomor_izin       = mysqli_real_escape_string($koneksi, $_POST['nomor_izin']);
    $omzet            = mysqli_real_escape_string($koneksi, $_POST['omzet']);
    $jumlah_produksi  = mysqli_real_escape_string($koneksi, $_POST['jumlah_produksi']);
    $jumlah_karyawan  = mysqli_real_escape_string($koneksi, $_POST['jumlah_karyawan']);
    $saluran_penjualan= mysqli_real_escape_string($koneksi, $_POST['saluran_penjualan']);
    $harga_rata2      = mysqli_real_escape_string($koneksi, $_POST['harga_rata2']);
    $permodalan       = mysqli_real_escape_string($koneksi, $_POST['permodalan']);
    $kendala          = mysqli_real_escape_string($koneksi, $_POST['kendala']);
    $kebutuhan        = mysqli_real_escape_string($koneksi, $_POST['kebutuhan']);
    $medsos           = mysqli_real_escape_string($koneksi, $_POST['medsos']);

    // Upload file (foto, logo)
    $uploadDir = __DIR__ . "/../uploads/"; 
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    function uploadFile($fieldName, $uploadDir) {
        if (!empty($_FILES[$fieldName]['name'])) {
            $ext = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
            $fileName = $fieldName . "_" . time() . "." . $ext;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
                return $fileName; // simpan nama file saja di DB
            }
        }
        return null;
    }

    $foto_produk  = uploadFile("foto_produk", $uploadDir);
    $foto_pemilik = uploadFile("foto_pemilik", $uploadDir);
    $logo_brand   = uploadFile("logo_brand", $uploadDir);

    // query insert
    $sql = "INSERT INTO umkm 
            (id_keluarga, nama_usaha, jenis_usaha, alamat_usaha, produk, tahun_mulai, nomor_izin, omzet, 
             jumlah_produksi, jumlah_karyawan, saluran_penjualan, harga_rata2, permodalan, kendala, kebutuhan, 
             foto_produk, foto_pemilik, logo_brand, medsos, created_at, updated_at) 
            VALUES 
            ('$id_keluarga', '$nama_usaha', '$jenis_usaha', '$alamat_usaha', '$produk', '$tahun_mulai', '$nomor_izin', '$omzet',
             '$jumlah_produksi', '$jumlah_karyawan', '$saluran_penjualan', '$harga_rata2', '$permodalan', '$kendala', '$kebutuhan',
             '$foto_produk', '$foto_pemilik', '$logo_brand', '$medsos', NOW(), NOW())";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: index.php?page=umkm");
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    header("Location: index.php?page=umkm");
    exit;
}
