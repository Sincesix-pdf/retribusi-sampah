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

    function updateJenisLayanan() {
        if (!jenisRetribusiSelect || !jenisLayananSelect) return;

        if (jenisRetribusiSelect.value === "tidak_tetap") {
            jenisLayananSelect.value = 4;
            jenisLayananSelect.setAttribute("readonly", "readonly");
        } else {
            jenisLayananSelect.removeAttribute("readonly");
        }
    }

    if (jenisRetribusiSelect) {
        jenisRetribusiSelect.addEventListener("change", updateJenisLayanan);
        updateJenisLayanan();
    }

    // ==================== Perhitungan Tagihan Tidak Tetap ====================
    const jenisTarifSelect = document.getElementById("jenisTarifSelect");
    const volumeInput = document.getElementById("volumeInput");
    const tarifInput = document.getElementById("tarifInput");
    const totalInput = document.getElementById("totalInput");

    function updateTotal() {
        if (!jenisTarifSelect || !volumeInput || !tarifInput || !totalInput)
            return;

        const tarifPerKubik =
            parseFloat(
                jenisTarifSelect.options[jenisTarifSelect.selectedIndex]
                    ?.dataset.tarif
            ) || 0;
        const volume = parseFloat(volumeInput.value) || 0;
        tarifInput.value = tarifPerKubik;
        totalInput.value = tarifPerKubik * volume;
    }

    if (jenisTarifSelect && volumeInput) {
        jenisTarifSelect.addEventListener("change", updateTotal);
        volumeInput.addEventListener("input", updateTotal);
    }
});

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
