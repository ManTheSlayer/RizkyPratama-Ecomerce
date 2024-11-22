<section class="pesanan" id="pesanan">
  <div class="container-pesanan">
    <div class="container-barang">
      <div class="barang">
        <?php
        include("../php/config.php");
        $result = mysqli_query($con, "SELECT * FROM pesanan WHERE id_user = '$id_user' AND status_pesanan = 'selesai' ORDER BY id DESC");
        if (mysqli_num_rows($result) > 0) {
          while ($proses = mysqli_fetch_assoc($result)):
            ?>
            <div class="barang-bayar">
              <table>
                <thead>
                  <tr>
                    <th>Produk</th>
                    <th>Ekspedisi</th>
                    <th>Total Harga</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <?= $proses['nama_produk'] ?>
                    </td>
                    <td>
                      <?= $proses['ekspedisi'] ?>
                    </td>
                    <td>Rp
                      <?= number_format($proses['final_total']) ?>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="total-price">
                <p class="pesanan-selesai">Pesanan anda telah selesai</p>
                <a href="detail/detail-selesai.php?id=<?= $proses["id"] ?>"><button class="btn">Detail</button></a>
              </div>
            </div>
          <?php endwhile;

        } else {
          echo '<div class="kosong">
                   <p>Belum ada pesanan</p>
                   </div>';
        }
        ?>
      </div>
    </div>
  </div>
</section>