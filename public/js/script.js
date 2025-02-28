document.addEventListener("DOMContentLoaded", function () {
    window.addEventListener("scroll", function () {
        var navbar = document.getElementById("navbar");
        if (window.scrollY > 10) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });
});

function togglePassword(fieldId, iconId) {
    let passwordField = document.getElementById(fieldId);
    let icon = document.getElementById(iconId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.textContent = "visibility_off";
    } else {
        passwordField.type = "password";
        icon.textContent = "visibility";
    }
}

// dropdown kelurahan
document.addEventListener("DOMContentLoaded", function () {
    let kecamatanSelect = document.getElementById("kecamatan_id");
    let kelurahanSelect = document.getElementById("kelurahan_id");

    function loadKelurahan(kecamatan_id) {
        if (!kecamatan_id) {
            kelurahanSelect.innerHTML =
                '<option value="">Semua Kelurahan</option>';
            return;
        }

        fetch(`/get-kelurahan?kecamatan_id=${kecamatan_id}`)
            .then((response) => response.json())
            .then((data) => {
                kelurahanSelect.innerHTML =
                    '<option value="">Semua Kelurahan</option>';
                data.forEach((kelurahan) => {
                    let selected =
                        kelurahan.id == "{{ request('kelurahan_id') }}"
                            ? "selected"
                            : "";
                    kelurahanSelect.innerHTML += `<option value="${kelurahan.id}" ${selected}>${kelurahan.nama}</option>`;
                });
            });
    }

    kecamatanSelect.addEventListener("change", function () {
        loadKelurahan(this.value);
    });

    // Load kelurahan saat halaman dimuat jika ada filter kecamatan yang aktif
    if (kecamatanSelect.value) {
        loadKelurahan(kecamatanSelect.value);
    }
});

// template data tabel
$(document).ready(function () {
    $("#ViewTable").DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            zeroRecords: "Data tidak ditemukan",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "→",
                previous: "←",
            },
        },
    });
});

document.addEventListener("DOMContentLoaded", function () {
    let kecamatanSelect = document.getElementById("kecamatan_id");
    let kelurahanSelect = document.getElementById("kelurahan_id");

    // Ambil kelurahan_id dari atribut input hidden atau dataset
    let oldKelurahanID = kelurahanSelect.getAttribute("data-old");

    function loadKelurahan(kecamatan_id) {
        if (!kecamatan_id) {
            kelurahanSelect.innerHTML =
                '<option value="">Semua Kelurahan</option>';
            return;
        }

        fetch(`/get-kelurahan?kecamatan_id=${kecamatan_id}`)
            .then((response) => response.json())
            .then((data) => {
                kelurahanSelect.innerHTML =
                    '<option value="">Semua Kelurahan</option>';
                data.forEach((kelurahan) => {
                    let selected =
                        kelurahan.id == oldKelurahanID ? "selected" : "";
                    kelurahanSelect.innerHTML += `<option value="${kelurahan.id}" ${selected}>${kelurahan.nama}</option>`;
                });
            });
    }

    // Panggil saat halaman dimuat
    if (kecamatanSelect.value) {
        loadKelurahan(kecamatanSelect.value);
    }

    // Jalankan loadKelurahan setiap kali kecamatan berubah
    kecamatanSelect.addEventListener("change", function () {
        loadKelurahan(this.value);
    });
});

// menyesuaikan ketika jenis retribusi = tidak tetap
document
    .getElementById("jenis_retribusi")
    .addEventListener("change", function () {
        let jenisLayananSelect = document.getElementById("jenis_layanan_id");

        if (this.value === "tidak_tetap") {
            jenisLayananSelect.value = 4; // ID untuk "Tidak Tetap"
            jenisLayananSelect.setAttribute("readonly", "readonly");
        } else {
            jenisLayananSelect.removeAttribute("readonly");
        }
    });

// Menyesuaikan pilihan saat reload karena error
window.onload = function () {
    let jenisRetribusi = document.getElementById("jenis_retribusi").value;
    let jenisLayananSelect = document.getElementById("jenis_layanan_id");
    if (jenisRetribusi === "tidak_tetap") {
        jenisLayananSelect.value = 4;
        jenisLayananSelect.setAttribute("readonly", "readonly");
    }
};
