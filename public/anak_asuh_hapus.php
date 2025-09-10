<?php
$id = $_GET['id'] ?? '';
if(!$id){
    echo "<p>ID anak tidak ditemukan!</p>";
    exit;
}

$stmt = $conn->prepare("DELETE FROM anak_asuh WHERE id=?");
$stmt->bind_param("i", $id);
if($stmt->execute()){
    echo "<script>alert('Data berhasil dihapus!'); location.href='index.php?page=anak_asuh';</script>";
} else {
    echo "<p>Terjadi kesalahan: ".$conn->error."</p>";
}
