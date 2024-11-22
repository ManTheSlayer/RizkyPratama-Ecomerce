    <!-- user section -->
    <section class="user-section">
      <div class="user">
        <h1>Daftar Kontak</h1>
        <div class="table">
          <div class="table-box">
            <!-- Table -->
            <table>
              <thead>
                <tr>
                  <th>Nomor</th>
                  <th>Subjek</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>No Telepon</th>
                  <th>Tanggal</th>
                  <th>Pesan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  include("../php/config.php");

                  $no = 1;

                  $result = mysqli_query($con, "SELECT * FROM kontak");

                  while($row = mysqli_fetch_assoc($result)) :
                  
                ?>
                <tr>
                  <td><?= $no; ?></td>
                  <td><?= $row["subjek"]?></td>
                  <td><?= $row["nama"]?></td>
                  <td><?= $row["email"]?></td>
                  <td><?= $row["phone"]?></td>
                  <td><?= $row["tanggal"]?></td>
                  <td><?= $row["pesan"]?></td>
                  <td>
                    <a href="hapus/hapus-kontak.php?id=<?= $row["id"]?>" onclick="return confirm('Ingin menghapus?');"><button class="btn-hapus">Hapus</button></a>
                  </td>
                </tr>
                <?php $no++; ?>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>