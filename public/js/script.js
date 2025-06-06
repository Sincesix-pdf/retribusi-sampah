document.addEventListener("DOMContentLoaded", function () {
    // ==================== Navbar Scroll Effect ====================
    const navbar = document.getElementById("navbar");
    if (navbar) {
        window.addEventListener("scroll", function () {
            navbar.classList.toggle("scrolled", window.scrollY > 10);
        });
    }

    // ==================== Toggle Password Visibility ====================
    window.togglePassword = function (fieldId, iconId) {
        const passwordField = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (passwordField && icon) {
            const isPassword = passwordField.type === "password";
            passwordField.type = isPassword ? "text" : "password";
            icon.textContent = isPassword ? "visibility_off" : "visibility";
        }
    };

    // ==================== Dropdown Kelurahan ====================
    const kecamatanSelect = document.getElementById("kecamatan_id");
    const kelurahanSelect = document.getElementById("kelurahan_id");
    const oldKelurahanID = kelurahanSelect?.getAttribute("data-old") || "";

    function loadKelurahan(kecamatan_id) {
        if (!kelurahanSelect || !kecamatan_id) return;

        kelurahanSelect.innerHTML = '<option value="">Semua Kelurahan</option>';
        fetch(`/get-kelurahan?kecamatan_id=${kecamatan_id}`)
            .then((response) => response.json())
            .then((data) => {
                data.forEach((kelurahan) => {
                    const selected =
                        kelurahan.id == oldKelurahanID ? "selected" : "";
                    kelurahanSelect.innerHTML += `<option value="${kelurahan.id}" ${selected}>${kelurahan.nama}</option>`;
                });
            });
    }

    if (kecamatanSelect) {
        kecamatanSelect.addEventListener("change", function () {
            loadKelurahan(this.value);
        });

        if (kecamatanSelect.value) {
            loadKelurahan(kecamatanSelect.value);
        }
    }

    // ==================== DataTables inisial ====================
    const wargaTable = document.querySelector("#tabel-warga");
    const diajukanTable = document.querySelector("#tabel-diajukan");
    const disetujuiTable = document.querySelector("#tabel-disetujui");

    if (wargaTable && $.fn.DataTable) {
        $("#tabel-warga").DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"],
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    next: '<i class="fas fa-chevron-right"></i>',
                    previous: '<i class="fas fa-chevron-left"></i>',
                },
                zeroRecords: "Data tidak ditemukan",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(filter dari total _MAX_ data)",
            },
        });
    }

    if (diajukanTable && $.fn.DataTable) {
        $("#tabel-diajukan").DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"],
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    next: '<i class="fas fa-chevron-right"></i>',
                    previous: '<i class="fas fa-chevron-left"></i>',
                },
                zeroRecords: "Data tidak ditemukan",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(filter dari total _MAX_ data)",
            },
        });
    }

    if (disetujuiTable && $.fn.DataTable) {
        $("#tabel-disetujui").DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"],
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    next: '<i class="fas fa-chevron-right"></i>',
                    previous: '<i class="fas fa-chevron-left"></i>',
                },
                zeroRecords: "Data tidak ditemukan",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(filter dari total _MAX_ data)",
            },
        });
    }

    // ==================== Jenis Retribusi Dropdown ====================
    const jenisRetribusiSelect = document.getElementById("jenis_retribusi");
    const jenisLayananSelect = document.getElementById("jenis_layanan_id");
    const jenisLayananError = document.getElementById("jenisLayananError");

    function updateJenisLayanan() {
        if (!jenisRetribusiSelect || !jenisLayananSelect || !jenisLayananError)
            return;

        const jenisRetribusi = jenisRetribusiSelect.value;
        const jenisLayanan = jenisLayananSelect.value;

        // Misal ID 4 adalah jenis layanan tidak tetap
        const layananTidakTetapId = "4";

        if (jenisRetribusi === "tidak_tetap") {
            jenisLayananSelect.value = layananTidakTetapId;
            jenisLayananSelect.setAttribute("readonly", "readonly");
            jenisLayananError.classList.add("d-none");
        } else {
            jenisLayananSelect.removeAttribute("readonly");

            if (jenisLayanan === layananTidakTetapId) {
                jenisLayananError.classList.remove("d-none");
            } else {
                jenisLayananError.classList.add("d-none");
            }
        }
    }

    if (jenisRetribusiSelect) {
        jenisRetribusiSelect.addEventListener("change", updateJenisLayanan);
        jenisLayananSelect.addEventListener("change", updateJenisLayanan);
        updateJenisLayanan();
    }
});

// ==================== Perhitungan Tagihan Tidak Tetap ====================
const wargaSelect = document.getElementById('wargaSelect');
const volumeInput = document.getElementById('volumeInput');
const tarifInput = document.getElementById('tarifInput');
const totalInput = document.getElementById('totalInput');

function updateTarif() {
    if (!wargaSelect || !tarifInput) return;
    const selected = wargaSelect.selectedOptions[0];
    const tarif = selected ? selected.getAttribute('data-tarif') : 0;
    tarifInput.value = tarif;
    updateTotal();
}

function updateTotal() {
    if (!tarifInput || !volumeInput || !totalInput) return;
    const tarif = parseFloat(tarifInput.value) || 0;
    const volume = parseFloat(volumeInput.value) || 0;
    totalInput.value = tarif * volume;
}

if (wargaSelect && volumeInput && tarifInput && totalInput) {
    wargaSelect.addEventListener('change', updateTarif);
    volumeInput.addEventListener('input', updateTotal);
    updateTarif(); // trigger awal
}

// ==================== Checkbox "Pilih Semua" ====================
document.querySelectorAll("#checkAll").forEach((checkAllBox) => {
    checkAllBox.addEventListener("change", function () {
        const targetGroup = this.getAttribute("data-target");

        const checkboxes = document.querySelectorAll(
            `input[data-group="${targetGroup}"]`
        );
        checkboxes.forEach((checkbox) => {
            checkbox.checked = this.checked;
        });
    });
});

// ==================== Validasi Tombol "Setujui" ====================
document
    .getElementById("setujuiTidakTetap")
    .addEventListener("click", function (e) {
        const selected = document.querySelectorAll(
            'input[name="tagihan_id[]"][data-group="tidak-tetap"]:checked'
        );
        if (selected.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Pilih data yang akan disetujui!",
                confirmButtonColor: "#3085d6",
            });
        }
    });

// ==================== Tombol "Tolak" Buka Modal ====================
document
    .getElementById("tolakTidakTetapBtn")
    .addEventListener("click", function (e) {
        let selected = [];
        document
            .querySelectorAll(
                'input[name="tagihan_id[]"][data-group="tidak-tetap"]:checked'
            )
            .forEach(function (checkbox) {
                selected.push(checkbox.value);
            });

        if (selected.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Pilih data yang akan ditolak!",
                confirmButtonColor: "#d33",
            });
            return false;
        }

        document.getElementById("tagihanIdsTolak").value = selected.join(",");
    });
