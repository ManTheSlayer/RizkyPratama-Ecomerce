    <!-- user section -->
    <section class="user-section">
      <div class="user">
        <h1>Daftar User</h1>
        <div class="table">
          <div class="table-box">
            <!-- Table -->
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Foto Profil</th>
                  <th>Nama Lengkap</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>No Telepon</th>
                  <th>Alamat</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  include("../php/config.php");

                  $no = 1;

                  $result = mysqli_query($con, "SELECT * FROM user");

                  while($row = mysqli_fetch_assoc($result)) :
                  
                ?>
                <tr>
                  <td><?= $no; ?></td>
                  <td><img src="asset/img/users/<?= htmlspecialchars($row["foto_profil"] ?: 'kosong.jpg') ?>" alt="Image" style="width: 100px; height: auto;"></td>
                  <td><?= $row["nama"]?></td>
                  <td><?= $row["username"]?></td>
                  <td><?= $row["email"]?></td>
                  <td><?= $row["phone"]?></td>
                  <td><?= $row["alamat"]?></td>
                  <td>
                    <a href="edit_user.php?id=<?= $row['id'] ?>"><button class="btn-edit">Edit</button></a>
                    <a href="hapus_user.php?id=<?= $row["id"]?>" onclick="return confirm('Ingin menghapus?');"><button class="btn-hapus">Hapus</button></a>
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