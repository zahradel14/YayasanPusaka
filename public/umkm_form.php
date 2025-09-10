<?php
include __DIR__ . "/../backend/koneksi.php";

// ambil data keluarga asuh untuk dropdown
$sql_keluarga = "SELECT id, nama_ibu FROM keluarga_asuh ORDER BY nama_ibu ASC";
$result_keluarga = mysqli_query($koneksi, $sql_keluarga);
?>

<section class="dashboard">
    <div class="card">
        <h2>Tambah Data UMKM</h2>
    </div>
</section>

<div class="form-container" style="max-width:800px; margin:20px auto; background:#fff; padding:20px; border:1px solid #ddd; border-radius:8px;">
    <form action="umkm_simpan.php" method="POST" enctype="multipart/form-data" 
          style="display:flex; flex-direction:column; gap:15px;">
        
        <!-- Pemilik -->
        <div class="form-group">
            <label for="id_keluarga" style="font-weight:bold;">Pemilik (Nama Ibu)</label>
            <select name="id_keluarga" id="id_keluarga" required 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                <option value="">-- Pilih Pemilik --</option>
                <?php while($row = mysqli_fetch_assoc($result_keluarga)) { ?>
                    <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['nama_ibu']); ?></option>
                <?php } ?>
            </select>
        </div>

        <!-- Data Usaha -->
        <h3>Data Usaha</h3>
        <div class="form-group">
            <label for="nama_usaha" style="font-weight:bold;">Nama Usaha/Brand</label>
            <input type="text" name="nama_usaha" id="nama_usaha" required style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label for="alamat_usaha" style="font-weight:bold;">Alamat Usaha</label>
            <textarea name="alamat_usaha" id="alamat_usaha" required
                      style="width:100%; padding:8px; height:70px;"></textarea>
        </div>

        <div class="form-group">
            <label for="jenis_usaha" style="font-weight:bold;">Jenis Usaha</label>
            <input type="text" name="jenis_usaha" id="jenis_usaha" required style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label for="produk" style="font-weight:bold;">Produk yang Dijual</label>
            <input type="text" name="produk" id="produk" style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label for="tahun_mulai" style="font-weight:bold;">Tahun Mulai</label>
            <input type="number" name="tahun_mulai" id="tahun_mulai" min="1900" max="<?= date('Y') ?>" style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label for="nomor_izin" style="font-weight:bold;">Nomor Izin Usaha</label>
            <input type="text" name="nomor_izin" id="nomor_izin" style="width:100%; padding:8px;">
        </div>

        <!-- Produksi & Penjualan -->
        <h3>Produksi & Penjualan</h3>
        <div class="form-group">
            <label for="omzet" style="font-weight:bold;">Rata-rata Omzet per Bulan (Rp)</label>
            <input type="number" name="omzet" id="omzet" min="0" style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label for="jumlah_produksi" style="font-weight:bold;">Jumlah Produksi (per hari/minggu)</label>
            <input type="text" name="jumlah_produksi" id="jumlah_produksi" style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label for="jumlah_karyawan" style="font-weight:bold;">Jumlah Karyawan</label>
            <input type="number" name="jumlah_karyawan" id="jumlah_karyawan" style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label for="saluran_penjualan" style="font-weight:bold;">Saluran Penjualan</label>
            <input type="text" name="saluran_penjualan" id="saluran_penjualan" placeholder="Offline, Online, Marketplace, Titip Jual" style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label for="harga_rata2" style="font-weight:bold;">Harga Rata-rata Produk</label>
            <input type="text" name="harga_rata2" id="harga_rata2" style="width:100%; padding:8px;">
        </div>

        <!-- Dukungan & Kebutuhan -->
        <h3>Dukungan & Kebutuhan</h3>
        <div class="form-group">
            <label for="permodalan" style="font-weight:bold;">Permodalan</label>
            <input type="text" name="permodalan" id="permodalan" placeholder="Pribadi, Pinjaman, Bantuan" style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label for="kendala" style="font-weight:bold;">Kendala Usaha</label>
            <textarea name="kendala" id="kendala" style="width:100%; padding:8px; height:70px;"></textarea>
        </div>

        <div class="form-group">
            <label for="kebutuhan" style="font-weight:bold;">Kebutuhan Utama</label>
            <input type="text" name="kebutuhan" id="kebutuhan" style="width:100%; padding:8px;">
        </div>

        <!-- Dokumentasi -->
        <h3>Dokumentasi</h3>
        <div class="form-group">
            <label for="foto_produk" style="font-weight:bold;">Foto Produk</label>
            <input type="file" name="foto_produk" id="foto_produk" accept="image/*">
        </div>

        <div class="form-group">
            <label for="foto_pemilik" style="font-weight:bold;">Foto Pemilik</label>
            <input type="file" name="foto_pemilik" id="foto_pemilik" accept="image/*">
        </div>

        <div class="form-group">
            <label for="logo_brand" style="font-weight:bold;">Logo / Branding</label>
            <input type="file" name="logo_brand" id="logo_brand" accept="image/*">
        </div>

        <div class="form-group">
            <label for="medsos" style="font-weight:bold;">Media Sosial / Marketplace</label>
            <input type="text" name="medsos" id="medsos" placeholder="Instagram, Shopee, dll." style="width:100%; padding:8px;">
        </div>

        <!-- Tombol -->
        <div style="display:flex; gap:10px; justify-content:flex-end;">
            <a href="index.php?page=umkm" 
               style="padding:10px 16px; background:#6c757d; color:#fff; border-radius:6px; text-decoration:none;">
                Batal
            </a>
            <button type="submit" 
                    style="padding:10px 16px; background:#2e59d9; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:bold;">
                Simpan
            </button>
        </div>
    </form>
</div>
