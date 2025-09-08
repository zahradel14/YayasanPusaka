<section class="dashboard">
    <div class="card">
        <h2>Total Anak Asuh</h2>
        <p><?php echo $umkmCount; ?></p>
    </div>
    <div class="card">
        <h2>Total Penerima Bantuan</h2>
        <p><?php echo $donorCount; ?></p>
    </div>
    <div class="card">
        <h2>Total Bantuan yang dikeluarkan</h2>
        <p>Rp <?php echo number_format($totalAid, 0, ',', '.'); ?></p>
    </div>
</section>
