// toggle claas avtive hamburger menu=======================================================================
const navbarNav = document.querySelector(".navbar-nav");
const hamburgerMenu = document.querySelector("#hamburger-menu");

document.querySelector("#hamburger-menu").onclick = (e) => {
  navbarNav.classList.toggle("active");
  e.preventDefault();
};

// toggle class active search form==========================================================================
const searchForm = document.querySelector(".search-form");
const searchBox = document.querySelector("#search-box");

document.querySelector("#search-button").onclick = (e) => {
  searchForm.classList.toggle("active");
  searchBox.focus();
  e.preventDefault();
};

// klik menu di halaman akun untuk menampilkan===============================================================
document.addEventListener("DOMContentLoaded", function () {
  const menuAkun = document.getElementById("menu-akun");
  const menuPesanan = document.getElementById("menu-pesanan");
  const menuGantipw = document.getElementById("menu-gantipw");

  const akunSection = document.getElementById("akun");
  const pesananSection = document.getElementById("pesanan");
  const changePwSection = document.getElementById("changepw");

  menuAkun.addEventListener("click", function (event) {
    event.preventDefault(); // Mencegah tindakan default tautan
    showSection(akunSection);
  });

  menuPesanan.addEventListener("click", function (event) {
    event.preventDefault(); // Mencegah tindakan default tautan
    showSection(pesananSection);
  });

  menuGantipw.addEventListener("click", function (event) {
    event.preventDefault(); // Mencegah tindakan default tautan
    showSection(changePwSection);
  });

  function showSection(section) {
    akunSection.style.display = "none";
    pesananSection.style.display = "none";
    changePwSection.style.display = "none";

    section.style.display = "block";
  }
});

// klik menghilangkan nav diluar element===============================================================
const hamburger = document.querySelector("#hamburger-menu");
const searchButton = document.querySelector("#search-button");

document.addEventListener("click", function (e) {
  if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
    navbarNav.classList.remove("active");
  }

  if (!searchButton.contains(e.target) && !searchForm.contains(e.target)) {
    searchForm.classList.remove("active");
  }
});

// menambahkan ke keranjang pada rekomendasi==================================================================
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".shopping-cart-button-index").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      if (!loggedIn) {
        window.location.href = "login.php";
        return;
      }

      const idProduk = this.getAttribute("data-id");
      const gambar = this.getAttribute("data-gambar");
      const nama = this.getAttribute("data-nama");
      const kategori = this.getAttribute("data-kategori");
      const harga = this.getAttribute("data-harga");
      const berat = this.getAttribute("data-berat");

      fetch("cart-rekom.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          id_produk: idProduk,
          gambar: gambar,
          nama: nama,
          kategori: kategori,
          harga: harga,
          berat: berat,
        }),
      })
        .then((response) => response.text())
        .then((result) => {
          alert(result);
          if (result === "Item berhasil ditambahkan ke keranjang") {
            window.location.href = "keranjang.php";
          }
        })
        .catch((error) => console.error("Error:", error));
    });
  });
});

// search==================================================================================
document.getElementById("search-label").addEventListener("click", function () {
  document.getElementById("search-form").submit();
});

// update kuantitas keranjang===========================================================
function updateQuantity(button, change) {
  var form = button.closest("form");
  var quantityInput = form.querySelector(".kuantitas");
  var currentQuantity = parseInt(quantityInput.value);
  var newQuantity = currentQuantity + change;

  if (newQuantity >= 1) {
    quantityInput.value = newQuantity;

    // Ambil data yang diperlukan dari form
    var cart_id = form.querySelector('input[name="cart_id"]').value;

    // Buat data form yang akan dikirim
    var formData = new FormData();
    formData.append("cart_id", cart_id);
    formData.append("cart_quantity", newQuantity);

    // Kirim data menggunakan Ajax
    fetch("update_quantity.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Update total harga dan tampilan lainnya jika perlu
          console.log("Update berhasil");
          location.reload();
        } else {
          console.log("Update gagal");
        }
      })
      .catch((error) => console.error("Error:", error));
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var quantityInputs = document.querySelectorAll(".kuantitas");
  quantityInputs.forEach(function (input) {
    input.addEventListener("keydown", function (event) {
      if (event.key === "Enter") {
        event.preventDefault();
        submitForm(input);
      }
    });

    // Tambahkan event listener untuk mendeteksi kapan keyboard tidak aktif
    var timeout;
    input.addEventListener("input", function () {
      clearTimeout(timeout);
      timeout = setTimeout(function () {
        submitForm(input);
      }, 1000); // Waktu tunda (ms) setelah keyboard tidak aktif
    });
  });

  function submitForm(input) {
    var form = input.closest("form");

    // Ambil data yang diperlukan dari form
    var cart_id = form.querySelector('input[name="cart_id"]').value;
    var cart_quantity = input.value;

    // Buat data form yang akan dikirim
    var formData = new FormData();
    formData.append("cart_id", cart_id);
    formData.append("cart_quantity", cart_quantity);

    // Kirim data menggunakan Ajax
    fetch("update_quantity.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Update total harga dan tampilan lainnya jika perlu
          console.log("Update berhasil");
          location.reload();
        } else {
          console.log("Update gagal");
        }
      })
      .catch((error) => console.error("Error:", error));
  }

  // Tambahkan event listener untuk checkbox
  var checkboxes = document.querySelectorAll(".item-checkbox");
  checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
      updateSelectedCount();
    });
  });

  // menghitung item yang terpilih
  function updateSelectedCount() {
    var selectedCheckboxes = document.querySelectorAll(
      ".item-checkbox:checked"
    );
    var selectedCount = selectedCheckboxes.length;
    var selectedCountText = "Total (" + selectedCount + " item terpilih)";

    // Update kedua elemen dengan ID "selectedCount"
    document.querySelectorAll("#selectedCount").forEach(function (element) {
      element.textContent = selectedCountText;
    });
  }
});
