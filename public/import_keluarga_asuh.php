<?php
include __DIR__ . "/../backend/koneksi.php";

if (isset($_FILES['file_excel']) && $_FILES['file_excel']['error'] == 0) {
    $fileTmp = $_FILES['file_excel']['tmp_name'];
    $handle = fopen($fileTmp, "r");

    if ($handle !== false) {
        // Lewati header baris pertama
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Sesuaikan urutan kolom dengan file
            $nip = $conn->real_escape_string($data[0]);
            $nama_alm = $conn->real_escape_string($data[1]);
            $jabatan = $conn->real_escape_string($data[2]);
            $wilayah = $conn->real_escape_string($data[3]);
            $nama_ibu = $conn->real_escape_string($data[4]);
            $no_telp = $conn->real_escape_string($data[5]);
            $alamat = $conn->real_escape_string($data[6]);

            $conn->query("INSERT INTO keluarga_asuh (nip_alm, nama_alm_pegawai, jabatan_terakhir, wilayah, nama_ibu, no_telp, alamat) 
                          VALUES ('$nip','$nama_alm','$jabatan','$wilayah','$nama_ibu','$no_telp','$alamat')");
        }
        fclose($handle);
    }
}

header("Location: index.php?page=keluarga_asuh");
exit;
?>
