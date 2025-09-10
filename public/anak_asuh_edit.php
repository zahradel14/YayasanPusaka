<?php
$id = $_GET['id'] ?? '';
if(!$id){
    echo "<p>ID anak tidak ditemukan!</p>";
    exit;
}

// Ambil data anak beserta keluarga
$stmt = $conn->prepare("SELECT a.*, k.nama_ibu FROM anak_asuh a JOIN keluarga_asuh k ON a.keluarga_asuh_id=k.id WHERE a.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$anak = $res->fetch_assoc();
if(!$anak){
    echo "<p>Data anak tidak ditemukan!</p>";
    exit;
}

// Update data
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nama = $_POST['nama_anak'] ?? '';
    $ttl  = $_POST['ttl_anak'] ?? '';
    $jenjang = $_POST['jenjang'] ?? '';
    $sekolah = $_POST['sekolah'] ?? '';
    $status = $_POST['status_tanggungan'] ?? '';

    $update = $conn->prepare("UPDATE anak_asuh SET nama_anak=?, ttl_anak=?, jenjang=?, sekolah=?, status_tanggungan=? WHERE id=?");
    $update->bind_param("sssssi", $nama, $ttl, $jenjang, $sekolah, $status, $id);
    if($update->execute()){
        echo "<script>alert('Data berhasil diperbarui!'); location.href='index.php?page=anak_asuh';</script>";
        exit;
    } else {
        echo "<p>Terjadi kesalahan: ".$conn->error."</p>";
    }
}
?>

<h2>Edit Data Anak Asuh</h2>
<form method="post">
    <label>Nama Anak:</label>
    <input type="text" name="nama_anak" value="<?= htmlspecialchars($anak['nama_anak']) ?>" required>

    <label>TTL:</label>
    <input type="text" name="ttl_anak" value="<?= htmlspecialchars($anak['ttl_anak']) ?>" required>

    <label>Jenjang:</label>
    <select name="jenjang" required>
        <?php
        $jenjangs = ['SD','SMP','SMA/SMK','D3','S1'];
        foreach($jenjangs as $j){
            $sel = $anak['jenjang']==$j?'selected':'';
            echo "<option $sel>$j</option>";
        }
        ?>
    </select>

    <label>Sekolah:</label>
    <input type="text" name="sekolah" value="<?= htmlspecialchars($anak['sekolah']) ?>">

    <label>Status:</label>
    <select name="status_tanggungan" required>
        <?php
        $statuses = ['Aktif','Lulus','Nonaktif'];
        foreach($statuses as $s){
            $sel = $anak['status_tanggungan']==$s?'selected':'';
            echo "<option $sel>$s</option>";
        }
        ?>
    </select>

    <label>Kategori:</label>
    <select name="kategori" required>
        <option value="Yatim" <?= ($anak['kategori']??'')=='Yatim'?'selected':'' ?>>Yatim</option>
        <option value="Yatim Piatu" <?= ($anak['kategori']??'')=='Yatim Piatu'?'selected':'' ?>>Yatim Piatu</option>
    </select>

    <label>Nama Ibu/Wali:</label>
    <input type="text" value="<?= htmlspecialchars($anak['nama_ibu']) ?>" disabled>

    <button type="submit">Simpan</button>
    <a href="index.php?page=anak_asuh">Batal</a>
</form>
