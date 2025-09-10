<?php
include __DIR__ . "/../backend/koneksi.php";

// header untuk download Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=keluarga_asuh.xls");

// query data
$sql = "SELECT * FROM keluarga_asuh ORDER BY created_at DESC";
$result = $conn->query($sql);

// tampilkan header kolom
echo "NIP Alm\tNama Alm. Pegawai\tJabatan\tWilayah\tNama Ibu\tNo Telp\tAlamat\n";

// tampilkan data baris
while($row = $result->fetch_assoc()) {
    echo $row['nip_alm']."\t".
         $row['nama_alm_pegawai']."\t".
         $row['jabatan_terakhir']."\t".
         $row['wilayah']."\t".
         $row['nama_ibu']."\t".
         $row['no_telp']."\t".
         $row['alamat']."\n";
}
exit;
?>
