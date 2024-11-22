    <!-- pesanan section -->
    <section class="pesanan-section">
      <div class="pesanan" style="position: static;">
        <h1>Daftar Pesanan</h1>
    <!-- diproses -->
    <div class="table">
      <div class="table-box">
        <div class="judul-box">
          <h1>Diproses</h1>
        </div>
        <!-- Table -->
        <table id="tabel-proses">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Produk</th>
              <th>Penerima</th>
              <th>Telepon</th>
              <th>Alamat</th>
              <th>Ekspedisi</th>
              <th>Total Harga</th>
              <th>Bukti Transfer</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
              include("../php/config.php");
               $result = mysqli_query($con, "SELECT * FROM pesanan WHERE status_pesanan = 'diproses'");
              while($proses = mysqli_fetch_assoc($result)) :
            ?>
            <tr>
              <input type="hidden" name="id" id="id" value="<?= $proses['id'] ?>"/>
              <td><?= $proses['tanggal'] ?></td>
              <td><?= $proses['nama_produk'] ?></td>
              <td><?= $proses['namaLengkap'] ?></td>
              <td><?= $proses['phone'] ?></td>
              <td><?= $proses['alamat'] ?></td>
              <td><?= $proses['ekspedisi'] ?></td>
              <td>Rp<?= number_format($proses['final_total']) ?></td>
              <td><a href="#" class="bukti" data-bukti="<?= $proses['bukti'] ?>"><button class="btn-edit" style="font-size: 0.7rem;">Cek bukti transfer</button></a></td>
              <td>
                <a href="#"><button class="btn-edit" id="kemas" data-id-proses="<?= $proses['id'] ?>" style="font-size: 0.7rem;">Kemas</button></a>
                <a href="#"><button class="btn-hapus" id="tolak" data-id-proses="<?= $proses['id'] ?>">Tolak</button></a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

        <!-- dikemas -->
        <div class="table">
          <div class="table-box">
            <div class="judul-box">
              <h1>Dikemas</h1>
            </div>
            <!-- Table -->
           <table>
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Produk</th>
                  <th>Penerima</th>
                  <th>Telepon</th>
                  <th>Alamat</th>
                  <th>Ekspedisi</th>
                  <th>Total Harga</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  include("../php/config.php");
                  $result = mysqli_query($con, "SELECT * FROM pesanan WHERE status_pesanan = 'dikemas'");
                  while($row = mysqli_fetch_assoc($result)) :
                ?>
                <tr>
                  <td><?= $row['tanggal'] ?></td>
                  <td><?= $row['nama_produk'] ?></td>
                  <td><?= $row['namaLengkap'] ?></td>
                  <td><?= $row['phone'] ?></td>
                  <td><?= $row['alamat'] ?></td>
                  <td><?= $row['ekspedisi'] ?></td>
                  <td>Rp<?= number_format($row['final_total']) ?></td>
                  <td>
                    <a href="update-resi.php?id=<?= $row['id'] ?>" onclick="return confirm('Kirim pesanan?');"><button class="btn-edit" id="kirim" style="font-size: 0.7rem;">Kirim</button></a>
                    <a href="hapus/hapus-kemas.php?id=<?= $row["id"]?>" onclick="return confirm('Ingin menghapus?');"><button class="btn-hapus">Hapus</button></a>
                  </td>
                </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- dikirim -->
        <div class="table">
          <div class="table-box">
            <div class="judul-box">
              <h1>Dikirim</h1>
            </div>
            <!-- Table -->
             <table id="tabel-kirim">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Produk</th>
                  <th>Penerima</th>
                  <th>Telepon</th>
                  <th>Alamat</th>
                  <th>Ekspedisi</th>
                  <th>Resi</th>
                  <th>Total Harga</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  include("../php/config.php");
                  $result = mysqli_query($con, "SELECT * FROM pesanan WHERE status_pesanan = 'dikirim'");
                  while($kirim = mysqli_fetch_assoc($result)) :
                ?>
                <tr>
                  <input type="hidden" name="id" id="id" value="<?= $kirim['id'] ?>"/>
                  <td><?= $kirim['tanggal'] ?></td>
                  <td><?= $kirim['nama_produk'] ?></td>
                  <td><?= $kirim['namaLengkap'] ?></td>
                  <td><?= $kirim['phone'] ?></td>
                  <td><?= $kirim['alamat'] ?></td>
                  <td><?= $kirim['ekspedisi'] ?></td>
                  <td><?= $kirim['resi'] ?></td>
                  <td>Rp<?= number_format($kirim['final_total']) ?></td>
                  <td>
                    <a href="#"><button class="btn-edit" id="selesai" data-id="<?= $kirim['id'] ?>" style="font-size: 0.7rem;">Selesai</button></a>
                    <a href="hapus/hapus-kirim.php?id=<?= $kirim["id"]?>" onclick="return confirm('Ingin menghapus?');"><button class="btn-hapus">Hapus</button></a>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- selesai -->
        <div class="table">
          <div class="table-box">
            <div class="judul-box">
              <h1>Selesai</h1>
            </div>
            <!-- Table -->
             <table>
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Produk</th>
                  <th>Penerima</th>
                  <th>Telepon</th>
                  <th>Alamat</th>
                  <th>Ekspedisi</th>
                  <th>Resi</th>
                  <th>Total Harga</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  include("../php/config.php");
                  $result = mysqli_query($con, "SELECT * FROM pesanan WHERE status_pesanan = 'selesai'");
                  while($selesai = mysqli_fetch_assoc($result)) :
                ?>
                <tr>
                  <input type="hidden" name="id" id="id" value="<?= $selesai['id'] ?>"/>
                  <td><?= $selesai['tanggal'] ?></td>
                  <td><?= $selesai['nama_produk'] ?></td>
                  <td><?= $selesai['namaLengkap'] ?></td>
                  <td><?= $selesai['phone'] ?></td>
                  <td><?= $selesai['alamat'] ?></td>
                  <td><?= $selesai['ekspedisi'] ?></td>
                  <td><?= $selesai['resi'] ?></td>
                  <td>Rp<?= number_format($selesai['final_total']) ?></td>
                  <td>
                    <a href="hapus/hapus-selesai.php?id=<?= $selesai["id"]?>" onclick="return confirm('Ingin menghapus?');"><button class="btn-hapus">Hapus</button></a>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- tolak -->
        <div class="table">
          <div class="table-box">
            <div class="judul-box">
              <h1>Ditolak</h1>
            </div>
            <!-- Table -->
            <table>
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Produk</th>
                  <th>Penerima</th>
                  <th>Telepon</th>
                  <th>Alamat</th>
                  <th>Ekspedisi</th>
                  <th>Total Harga</th>
                  <th>Bukti Transfer</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  include("../php/config.php");
                  $result = mysqli_query($con, "SELECT * FROM pesanan WHERE status_pesanan = 'ditolak'");
                  while($tolak = mysqli_fetch_assoc($result)) :
                ?>
                <tr>
                  <td><?= $tolak['tanggal'] ?></td>
                  <td><?= $tolak['nama_produk'] ?></td>
                  <td><?= $tolak['namaLengkap'] ?></td>
                  <td><?= $tolak['phone'] ?></td>
                  <td><?= $tolak['alamat'] ?></td>
                  <td><?= $tolak['ekspedisi'] ?></td>
                  <td>Rp<?= number_format($tolak['final_total']) ?></td>
                  <td><a href="#" class="bukti" data-bukti="<?= $tolak['bukti'] ?>"><button class="btn-edit" style="font-size: 0.7rem;">Cek bukti transfer</button></a></td>
                  <td>
                    <a href="hapus/hapus-tolak.php?id=<?= $tolak["id"]?>" onclick="return confirm('Ingin menghapus?');"><button class="btn-hapus">Hapus</button></a>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <div class="modal" id="modal-bayar">
      <div class="container">
        <i class="bx bx-x" id="btn-back" style="font-size: 2rem; padding: 0 0.5rem 0; color:#fff; cursor: pointer;"></i>
        <img src="" alt="Image" id="file-bukti" style="width: 800px; height: auto;">
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      // button kemas
      $(document).ready(function() {
        $('#tabel-proses').on('click', '#kemas', function(e) {
          e.preventDefault(); // Menghindari aksi default dari link
          // Memastikan konfirmasi sebelum melanjutkan
          if (!confirm('Kemas pesanan??')) {
            return;
          }
          // Mendapatkan ID
          var id = $(this).data('id-proses');
          // Kirim ID ke server menggunakan AJAX
          $.ajax({
            type: 'POST',
            url: 'php/proses_kemas.php', // Ganti dengan file PHP yang akan menangani penyisipan ke tabel kemas
            data: { id: id },
            success: function(response) {
              if(response === "success") {
                alert('Data telah berhasil dipindahkan ke tabel kemas.');
                location.reload(); // Refresh halaman setelah data berhasil dipindahkan
              } else {
                alert('Gagal memindahkan data: ' + response);
              }
            },
            error: function(xhr, status, error) {
              alert('Terjadi kesalahan: ' + error);
            }
          });
        });
      });

      // button tolak
      $(document).ready(function() {
        $('#tabel-proses').on('click', '#tolak', function(e) {
          e.preventDefault(); // Menghindari aksi default dari link
          // Memastikan konfirmasi sebelum melanjutkan
          if (!confirm('Tolak pesanan??')) {
            return;
          }
          // Mendapatkan ID
          var id = $(this).data('id-proses');
          // Kirim ID ke server menggunakan AJAX
          $.ajax({
            type: 'POST',
            url: 'php/proses_tolak.php', // Ganti dengan file PHP yang akan menangani penyisipan ke tabel kemas
            data: { id: id },
            success: function(response) {
              if(response === "success") {
                alert('Pesanan telah di tolak.');
                location.reload(); // Refresh halaman setelah data berhasil dipindahkan
              } else {
                alert('Gagal tolak pesanan: ' + response);
              }
            },
            error: function(xhr, status, error) {
              alert('Terjadi kesalahan: ' + error);
            }
          });
        });
      });

      // button selesai
      $(document).ready(function() {
        $('#tabel-kirim').on('click', '#selesai', function(e) {
          e.preventDefault(); // Menghindari aksi default dari link
          // Memastikan konfirmasi sebelum melanjutkan
          if (!confirm('Selesaikan pesanan?')) {
            return;
          }
          // Mendapatkan ID dari atribut data-id
          var id = $(this).data('id');
          // Kirim ID ke server menggunakan AJAX
          $.ajax({
            type: 'POST',
            url: 'php/proses_selesai.php', // Ganti dengan file PHP yang akan menangani penyisipan ke tabel selesai
            data: { id: id },
            success: function(response) {
              if(response === "success") {
                alert('Data telah berhasil dipindahkan ke tabel selesai.');
                location.reload(); // Refresh halaman setelah data berhasil dipindahkan
              } else {
                alert('Gagal memindahkan data: ' + response);
              }
            },
            error: function(xhr, status, error) {
              alert('Terjadi kesalahan: ' + error);
            }
          });
        });
      });


      // Tampilkan modal ketika tombol bayar diklik
      document.querySelectorAll(".bukti").forEach(function(element) {
        element.addEventListener("click", function(event) {
          event.preventDefault();
          const buktiSrc = event.currentTarget.dataset.bukti;
          document.getElementById("file-bukti").src = "asset/bukti/" + buktiSrc;
          document.getElementById("modal-bayar").style.display = "flex";
        });
      });

      document.getElementById("btn-back").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("modal-bayar").style.display = "none";
      });

      window.addEventListener("click", function(event) {
        if (event.target == document.getElementById("modal-bayar")) {
          document.getElementById("modal-bayar").style.display = "none";
        }
      });
    </script>