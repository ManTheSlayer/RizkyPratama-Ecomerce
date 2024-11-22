<!-- produk section -->
<section class="produk-section">
  <div class="produk">
    <h1>Daftar Produk</h1>

    <!-- case -->
    <div class="table">
      <div class="table-box">
        <div class="judul-box">
          <h1>Soft Case</h1>
          <a href="tambah-case.php"><button class="btn-tambah">Tambah Soft Case</button></a>
        </div>

        <!-- Table -->
        <table>
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Nama</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Terjual</th>
              <th>Bahan</th>
              <th>Model</th>
              <th>Berat</th>
              <th>Deskripsi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include("../php/config.php");
            $result = mysqli_query($con, "SELECT * FROM produk WHERE kategori = 'banner'");
            while ($row = mysqli_fetch_assoc($result)):
              ?>
              <tr>
                <td><img src="asset/img/produk/<?= $row['gambar'] ?>" alt="Image" style="width: 100px; height: auto;">
                </td>
                <td>
                  <?= $row['nama'] ?>
                </td>
                <td>Rp
                  <?= number_format($row['harga']) ?>
                </td>
                <td>
                  <?= $row['stok'] ?>
                </td>
                <td>
                  <?= $row['dibeli'] ?>
                </td>
                <td>
                  <?= $row['bahan'] ?>
                </td>
                <td>
                  <?= $row['model'] ?>
                </td>
                <td>
                  <?= $row['berat'] ?>
                </td>
                <td>
                  <?= $row['deskripsi'] ?>
                </td>
                <td>
                  <a href="edit-case.php?id=<?= $row['id'] ?>"><button class="btn-edit">Edit</button></a>
                  <a href="hapus-case.php?id=<?= $row["id"] ?>" onclick="return confirm('Ingin menghapus?');"><button
                      class="btn-hapus">Hapus</button></a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- charger -->
    <div class="table">
      <div class="table-box">
        <div class="judul-box">
          <h1>Charger</h1>
          <a href="tambah-charger.php"><button class="btn-tambah">Tambah Charger</button></a>
        </div>
        <!-- Table -->
        <table>
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Nama</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Terjual</th>
              <th>Bahan</th>
              <th>Model</th>
              <th>Berat</th>
              <th>Deskripsi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include("../php/config.php");
            $result = mysqli_query($con, "SELECT * FROM produk WHERE kategori = 'charger'");
            while ($row = mysqli_fetch_assoc($result)):
              ?>
              <tr>
                <td><img src="asset/img/produk/<?= $row['gambar'] ?>" alt="Image" style="width: 100px; height: auto;">
                </td>
                <td>
                  <?= $row['nama'] ?>
                </td>
                <td>Rp
                  <?= number_format($row['harga']) ?>
                </td>
                <td>
                  <?= $row['stok'] ?>
                </td>
                <td>
                  <?= $row['dibeli'] ?>
                </td>
                <td>
                  <?= $row['bahan'] ?>
                </td>
                <td>
                  <?= $row['model'] ?>
                </td>
                <td>
                  <?= $row['berat'] ?>
                </td>
                <td>
                  <?= $row['deskripsi'] ?>
                </td>
                <td>
                  <a href="edit-charger.php?id=<?= $row['id'] ?>"><button class="btn-edit">Edit</button></a>
                  <a href="hapus-charger.php?id=<?= $row["id"] ?>" onclick="return confirm('Ingin menghapus?');"><button
                      class="btn-hapus">Hapus</button></a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- aksesoris -->
    <div class="table">
      <div class="table-box">
        <div class="judul-box">
          <h1>Aksesoris</h1>
          <a href="tambah-aksesoris.php"><button class="btn-tambah">Tambah Aksesoris</button></a>
        </div>
        <!-- Table -->
        <table>
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Nama</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Terjual</th>
              <th>Bahan</th>
              <th>Model</th>
              <th>Berat</th>
              <th>Deskripsi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include("../php/config.php");
            $result = mysqli_query($con, "SELECT * FROM produk WHERE kategori = 'aksesoris'");
            while ($row = mysqli_fetch_assoc($result)):
              ?>
              <tr>
                <td><img src="asset/img/produk/<?= $row['gambar'] ?>" alt="Image" style="width: 100px; height: auto;">
                </td>
                <td>
                  <?= $row['nama'] ?>
                </td>
                <td>Rp
                  <?= number_format($row['harga']) ?>
                </td>
                <td>
                  <?= $row['stok'] ?>
                </td>
                <td>
                  <?= $row['dibeli'] ?>
                </td>
                <td>
                  <?= $row['bahan'] ?>
                </td>
                <td>
                  <?= $row['model'] ?>
                </td>
                <td>
                  <?= $row['berat'] ?>
                </td>
                <td>
                  <?= $row['deskripsi'] ?>
                </td>
                <td>
                  <a href="edit-aksesoris.php?id=<?= $row['id'] ?>"><button class="btn-edit">Edit</button></a>
                  <a href="hapus-aksesoris.php?id=<?= $row["id"] ?>" onclick="return confirm('Ingin menghapus?');"><button
                      class="btn-hapus">Hapus</button></a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>


  </div>
</section>