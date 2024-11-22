<!-- ekspedisi section -->
<section class="ekspedisi-section">
  <div class="ekspedisi">
    <h1>Jasa Ekspedisi</h1>
    <div class="table">
      <div class="table-box">
        <!-- Table -->
        <table>
          <thead>
            <tr>
              <th>Provinsi</th>
              <th>Kota</th>
              <th>Nama Ekspedisi</th>
              <th>Paket</th>
              <th>Tarif Ekspedisi</th>
              <!-- <th>Aksi</th> -->
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <select class="form-control" name="nama_provinsi"></select>
              </td>
              <td>
                <select class="form-control" name="nama_distrik" id="">
                  <option value=''>--Pilih Provinsi Dahulu--</option>
                </select>
              </td>
              <td>
                <select class="form-control" name="nama_ekspedisi" id="">
                  <option value=''>--Pilih Kota Dahulu--</option>
                </select>
              </td>
              <td>
                <select class="form-control" name="nama_paket" id="">
                  <option value=''>--Pilih Ekspedisi Dahulu--</option>
                </select>
              </td>
              <td><p name="total-ongkir">Rp0</p></td>
            </tr>
          </tbody>
          <input type="hidden" name="total_berat" value="200">
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Raja ongkir -->
<script src="jquery.min.js"></script>
<script>
$(document).ready(function() {
    $.ajax({
        type: 'post',
        url: 'dataprovinsi.php',
        success: function(hasil_provinsi) {
            $("select[name=nama_provinsi]").html(hasil_provinsi);
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: ", status, error); // Tambahkan log untuk kesalahan AJAX
        }
    });

    $("select[name=nama_provinsi]").on("change", function() {
        var id_provinsi_terpilih = $("option:selected", this).attr("id_provinsi");
        $.ajax({
            type: 'post',
            url: 'datadistrik.php',
            data: { id_provinsi: id_provinsi_terpilih },
            success: function(hasil_distrik) {
                $("select[name=nama_distrik]").html(hasil_distrik);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
            }
        });
    });

    $("select[name=nama_distrik]").on("change", function() {
        $.ajax({
            type: 'post',
            url: 'dataekspedisi.php',
            success: function(hasil_ekspedisi) {
                $("select[name=nama_ekspedisi]").html(hasil_ekspedisi);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
            }
        });
    });

    $("select[name=nama_ekspedisi]").on("change", function() {
        // mendapatkan ekspedisi yang dipilih
        var ekspedisi_terpilih = $("select[name=nama_ekspedisi]").val();
        // mendapatkan id_distrik yang dipilih pengguna
        var distrik_terpilih = $("option:selected", "select[name=nama_distrik]").attr("id_distrik");
        // mendapatkan total berat dari inputan
        var total_berat = $("input[name=total_berat]").val();
        $.ajax({
            type: 'post',
            url: 'datapaket.php',
            data: { ekspedisi: ekspedisi_terpilih, distrik: distrik_terpilih, berat: total_berat },
            success: function(hasil_paket) {
                $("select[name=nama_paket]").html(hasil_paket);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
            }
        });
    });

    $("select[name=nama_distrik]").on("change", function() {
        var prov = $("option:selected", this).attr("nama_provinsi");
        var dist = $("option:selected", this).attr("nama_distrik");
        var tipe = $("option:selected", this).attr("tipe_distrik");
        var kodepos = $("option:selected", this).attr("kodepos");

        $("input[name=provinsi]").val(prov);
        $("input[name=distrik]").val(dist);
        $("input[name=tipe]").val(tipe);
        $("input[name=kodepos]").val(kodepos);
    });

    $("select[name=nama_paket]").on("change", function() {
        var ongkir = $("option:selected", this).attr("ongkir");

        $("p[name=total-ongkir]").text("Rp" + Number(ongkir).toLocaleString());
    });
});
</script>

