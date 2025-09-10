<?php
$id = $_GET['id'] ?? '';
$edit = false;

if ($id) {
    $edit = true;
    $res = $conn->query("SELECT * FROM keluarga_asuh WHERE id = " . (int)$id);
    $keluarga = $res->fetch_assoc();

    $anakRes = $conn->query("SELECT * FROM anak_asuh WHERE keluarga_asuh_id = " . (int)$id . " ORDER BY id ASC");
    $anakList = $anakRes->fetch_all(MYSQLI_ASSOC);
}

// Simpan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();

    try {
        // --- Data keluarga ---
        $nip_alm    = $_POST['nip_alm'] ?? '';
        $nama_alm   = $_POST['nama_alm_pegawai'] ?? '';
        $jabatan    = $_POST['jabatan_terakhir'] ?? '';
        $meninggal  = $_POST['meninggal_tewas'] ?? '';
        $penyebab   = $_POST['penyebab_meninggal'] ?? '';
        $wilayah    = $_POST['wilayah'] ?? '';
        $nama_ibu   = $_POST['nama_ibu'] ?? '';
        $ttl_ibu    = $_POST['pekerjaan_ibu'] ?? '';
        $alamat     = $_POST['alamat'] ?? '';
        $kelurahan  = $_POST['kelurahan'] ?? '';
        $kecamatan  = $_POST['kecamatan'] ?? '';
        $kota       = $_POST['kota'] ?? '';
        $no_telp    = $_POST['no_telp'] ?? '';
        $catatan    = $_POST['catatan'] ?? '';

        // --- Data anak (array) ---
        $nama_anak   = $_POST['nama_anak'] ?? [];
        $ttl_anak    = $_POST['ttl_anak'] ?? [];
        $jenjang     = $_POST['jenjang'] ?? [];
        $sekolah     = $_POST['sekolah'] ?? [];
        $status_tg   = $_POST['status_tanggungan'] ?? [];

        if ($edit) {
            $stmt = $conn->prepare("UPDATE keluarga_asuh SET 
                nip_alm=?, nama_alm_pegawai=?, jabatan_terakhir=?, meninggal_tewas=?, penyebab_meninggal=?, wilayah=?,
                nama_ibu=?, pekerjaan_ibu=?, pekerjaan=?, alamat=?, kelurahan=?, kecamatan=?, kota=?, no_telp=?, catatan=?
                WHERE id=?");
            $stmt->bind_param(
                "sssssssssssssssi",
                $nip_alm, $nama_alm, $jabatan, $meninggal, $penyebab, $wilayah,
                $nama_ibu, $ttl_ibu, $pekerjaan, $alamat, $kelurahan, $kecamatan, $kota, $no_telp, $catatan,
                $id
            );
            $stmt->execute();

            $conn->query("DELETE FROM anak_asuh WHERE keluarga_asuh_id=" . (int)$id);
            $keluarga_id = (int)$id;
        } else {
            $stmt = $conn->prepare("INSERT INTO keluarga_asuh
                (nip_alm, nama_alm_pegawai, jabatan_terakhir, meninggal_tewas, penyebab_meninggal, wilayah,
                nama_ibu, pekerjaan_ibu, pekerjaan, alamat, kelurahan, kecamatan, kota, no_telp, catatan, created_at)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, NOW())");
            $stmt->bind_param(
                "sssssssssssssss",
                $nip_alm, $nama_alm, $jabatan, $meninggal, $penyebab, $wilayah,
                $nama_ibu, $ttl_ibu, $pekerjaan, $alamat, $kelurahan, $kecamatan, $kota, $no_telp, $catatan
            );
            $stmt->execute();
            $keluarga_id = $conn->insert_id;
        }

        // --- Insert anak ---
        $validJenjang = ['SD','SMP','SMA/SMK','D3','S1'];
        $validStatus  = ['Aktif','Lulus','Nonaktif'];

        if (!empty($nama_anak)) {
            $stmtAnak = $conn->prepare("INSERT INTO anak_asuh
                (keluarga_asuh_id, nama_anak, ttl_anak, jenjang, sekolah, status_tanggungan)
                VALUES (?,?,?,?,?,?)");

            foreach ($nama_anak as $i => $nm) {
                $nm = trim($nm ?? '');
                if ($nm === '') continue;

                $ttl   = trim($ttl_anak[$i] ?? '');
                $jenj  = in_array($jenjang[$i] ?? '', $validJenjang) ? $jenjang[$i] : 'SD';
                $sekol = trim($sekolah[$i] ?? '');
                $stat  = in_array($status_tg[$i] ?? '', $validStatus) ? $status_tg[$i] : 'Aktif';

                $stmtAnak->bind_param("isssss", $keluarga_id, $nm, $ttl, $jenj, $sekol, $stat);
                $stmtAnak->execute();
            }
        }

        $conn->commit();
        echo "<script>alert('Data keluarga & anak berhasil disimpan!');location.href='index.php?page=keluarga_asuh';</script>";
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Terjadi kesalahan saat menyimpan data: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>

<section class="dashboard">
  <div class="card">
    <h2><?= $edit ? 'Edit Keluarga Asuh' : 'Tambah Keluarga Asuh'; ?></h2>
    <form method="post">
      <div class="form-grid">
        <!-- 1. Data Alm. Pegawai -->
        <div class="card-section">
          <div class="section-header"><h3>1. Data Alm. Pegawai</h3></div>
          <div><label>NIP Alm:</label><input type="text" name="nip_alm" value="<?= $keluarga['nip_alm'] ?? '' ?>"></div>
          <div><label>Nama Alm. Pegawai:</label><input type="text" name="nama_alm_pegawai" value="<?= $keluarga['nama_alm_pegawai'] ?? '' ?>"></div>
          <div><label>Jabatan Terakhir:</label><input type="text" name="jabatan_terakhir" value="<?= $keluarga['jabatan_terakhir'] ?? '' ?>"></div>
          <div><label>Meninggal/Tewas:</label>
            <select name="meninggal_tewas">
              <option <?= ($keluarga['meninggal_tewas']??'')=='Meninggal'?'selected':'' ?>>Meninggal</option>
              <option <?= ($keluarga['meninggal_tewas']??'')=='Tewas'?'selected':'' ?>>Tewas</option>
            </select>
          </div>
          <div><label>Penyebab Meninggal:</label><input type="text" name="penyebab_meninggal" value="<?= $keluarga['penyebab_meninggal'] ?? '' ?>"></div>
          <div><label>Wilayah:</label><input type="text" name="wilayah" value="<?= $keluarga['wilayah'] ?? '' ?>"></div>
        </div>

        <!-- 2. Data Ibu/Wali -->
        <div class="card-section">
          <div class="section-header"><h3>2. Data Ibu / Wali</h3></div>
          <div><label>Nama Ibu:</label><input type="text" name="nama_ibu" value="<?= $keluarga['nama_ibu'] ?? '' ?>"></div>
          <div><label>Pekerjaan Ibu:</label><input type="text" name="pekerjaan_ibu" value="<?= $keluarga['pekerjaan_ibu'] ?? '' ?>"></div>
          <div><label>No. Telp:</label><input type="text" name="no_telp" value="<?= $keluarga['no_telp'] ?? '' ?>"></div>
        </div>
      </div>

      <!-- 3. Alamat & Catatan -->
      <div class="form-grid">
        <div class="card-section" style="grid-column:1/-1">
          <div class="section-header"><h3>3. Alamat</h3></div>
          <div><label>Alamat:</label><input type="text" name="alamat" value="<?= $keluarga['alamat'] ?? '' ?>"></div>
          <div><label>Kelurahan:</label><input type="text" name="kelurahan" value="<?= $keluarga['kelurahan'] ?? '' ?>"></div>
          <div><label>Kecamatan:</label><input type="text" name="kecamatan" value="<?= $keluarga['kecamatan'] ?? '' ?>"></div>
          <div><label>Kota:</label><input type="text" name="kota" value="<?= $keluarga['kota'] ?? '' ?>"></div>
          <div><label>Catatan:</label><textarea name="catatan" rows="3"><?= $keluarga['catatan'] ?? '' ?></textarea></div>
        </div>
      </div>

      <!-- 4. Data Anak -->
      <div class="form-grid">
        <div class="card-section" style="grid-column:1/-1">
          <div class="section-header"><h3>4. Data Anak</h3></div>
          <div id="anak-container">
            <?php
            if (!empty($anakList)) {
                $i = 1;
                foreach ($anakList as $anak) {
                    echo '<div class="anak-row">
                        <label>Anak ke-' . $i++ . '</label>
                        <input type="text" name="nama_anak[]" placeholder="Nama Anak" value="' . htmlspecialchars($anak['nama_anak']) . '" />
                        <input type="text" name="ttl_anak[]" placeholder="Tempat, Tgl Lahir" value="' . htmlspecialchars($anak['ttl_anak']) . '" />
                        <input type="text" name="jenjang[]" placeholder="Jenjang (SD/SMP/SMA/S1)" value="' . htmlspecialchars($anak['jenjang']) . '" />
                        <input type="text" name="sekolah[]" placeholder="Sekolah" value="' . htmlspecialchars($anak['sekolah']) . '" />
                        <select name="status_tanggungan[]">
                            <option ' . ($anak['status_tanggungan']=='Aktif'?'selected':'') . '>Aktif</option>
                            <option ' . ($anak['status_tanggungan']=='Lulus'?'selected':'') . '>Lulus</option>
                            <option ' . ($anak['status_tanggungan']=='Nonaktif'?'selected':'') . '>Nonaktif</option>
                        </select>
                        <button type="button" class="btn-remove" onclick="hapusAnak(this)">✖</button>
                    </div>';
                }
            } else {
                echo '<div class="anak-row">
                    <label>Anak ke-1</label>
                    <input type="text" name="nama_anak[]" placeholder="Nama Anak" />
                    <input type="text" name="ttl_anak[]" placeholder="Tempat, Tgl Lahir" />
                    <input type="text" name="jenjang[]" placeholder="Jenjang (SD/SMP/SMA/S1)" />
                    <input type="text" name="sekolah[]" placeholder="Sekolah" />
                    <select name="status_tanggungan[]">
                        <option>Aktif</option>
                        <option>Lulus</option>
                        <option>Nonaktif</option>
                    </select>
                    <button type="button" class="btn-remove" onclick="hapusAnak(this)">✖</button>
                </div>';
            }
            ?>
          </div>
          <div style="margin-top:10px;">
            <button type="button" class="btn-small" onclick="tambahAnak()">+ Tambah Anak</button>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-small">Simpan</button>
        <a href="index.php?page=keluarga_asuh" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</section>

<script>
function tambahAnak() {
    const container = document.getElementById('anak-container');
    const index = container.querySelectorAll('.anak-row').length + 1;

    const row = document.createElement('div');
    row.className = 'anak-row';
    row.innerHTML = `
        <label>Anak ke-${index}</label>
        <input type="text" name="nama_anak[]" placeholder="Nama Anak" />
        <input type="text" name="ttl_anak[]" placeholder="Tempat, Tgl Lahir" />
        <input type="text" name="jenjang[]" placeholder="Jenjang (SD/SMP/SMA/S1)" />
        <input type="text" name="sekolah[]" placeholder="Sekolah" />
        <select name="status_tanggungan[]">
            <option>Aktif</option>
            <option>Lulus</option>
            <option>Nonaktif</option>
        </select>
        <button type="button" class="btn-remove" onclick="hapusAnak(this)">✖</button>
    `;
    container.appendChild(row);
}

function hapusAnak(button) {
    button.closest('.anak-row').remove();
    const anakRows = document.querySelectorAll('#anak-container .anak-row');
    anakRows.forEach((row, i) => {
        row.querySelector('label').textContent = `Anak ke-${i+1}`;
    });
}
</script>

<style>
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:20px}
.card-section{background:#fff;padding:15px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,.05)}
.section-header{margin-bottom:15px}
label{display:block;font-weight:600;margin-bottom:5px;text-align:left}
input,select,textarea{width:100%;padding:8px 12px;border:1px solid #ccc;border-radius:5px;font-size:14px;box-sizing:border-box}
.form-actions{text-align:center;margin-top:20px}
.btn-small{background:#2e59d9;color:#fff;padding:6px 12px;font-size:13px;border:none;border-radius:4px;cursor:pointer}
.btn-cancel{margin-left:10px;color:#e74a3b;text-decoration:none}
.btn-cancel:hover{text-decoration:underline}

#anak-container{display:flex;flex-direction:column;gap:12px}
.anak-row{
  display:grid;
  grid-template-columns: 1.2fr 1fr .9fr 1fr .9fr auto; /* Nama | TTL | Jenjang | Sekolah | Status | X */
  gap:10px;align-items:center
}
.anak-row label{grid-column:1 / -1;margin:0 0 4px 0}
.btn-remove{background:#e74a3b;color:#fff;border:none;padding:5px 8px;font-size:12px;border-radius:50%;cursor:pointer;line-height:1}
.btn-remove:hover{background:#c0392b}
@media (max-width: 900px){
  .form-grid{grid-template-columns:1fr}
  .anak-row{grid-template-columns:1fr 1fr;}
  .anak-row select, 
  .anak-row input[name="sekolah[]"], 
  .anak-row select[name="status_tanggungan[]"]{grid-column:1 / -1}
}
</style>
