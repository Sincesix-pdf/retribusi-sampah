document.addEventListener("DOMContentLoaded", function () {
    // Navbar Scroll Effect
    window.addEventListener("scroll", function () {
        let navbar = document.getElementById("navbar");
        if (window.scrollY > 10) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });

    // Toggle Password Visibility
    window.togglePassword = function (fieldId, iconId) {
        let passwordField = document.getElementById(fieldId);
        let icon = document.getElementById(iconId);
        
        passwordField.type = passwordField.type === "password" ? "text" : "password";
        icon.textContent = passwordField.type === "password" ? "visibility" : "visibility_off";
    };

    // Dropdown Kelurahan
    let kecamatanSelect = document.getElementById("kecamatan_id");
    let kelurahanSelect = document.getElementById("kelurahan_id");
    let oldKelurahanID = kelurahanSelect?.getAttribute("data-old") || "";

    function loadKelurahan(kecamatan_id) {
        if (!kelurahanSelect) return;
        
        kelurahanSelect.innerHTML = '<option value="">Semua Kelurahan</option>';
        if (!kecamatan_id) return;
        
        fetch(`/get-kelurahan?kecamatan_id=${kecamatan_id}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(kelurahan => {
                    let selected = kelurahan.id == oldKelurahanID ? "selected" : "";
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

    // DataTable Initialization
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
            
        },
    });

    // Jenis Retribusi Logic
    let jenisRetribusiSelect = document.getElementById("jenis_retribusi");
    let jenisLayananSelect = document.getElementById("jenis_layanan_id");

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
        updateJenisLayanan(); // Panggil saat halaman dimuat
    }

    // Perhitungan Tagihan Tidak Tetap
    let jenisTarifSelect = document.getElementById("jenisTarifSelect");
    let volumeInput = document.getElementById("volumeInput");
    let tarifInput = document.getElementById("tarifInput");
    let totalInput = document.getElementById("totalInput");

    function updateTotal() {
        if (!jenisTarifSelect || !volumeInput || !tarifInput || !totalInput) return;
        
        let tarifPerKubik = parseFloat(jenisTarifSelect.options[jenisTarifSelect.selectedIndex]?.dataset.tarif) || 0;
        let volume = parseFloat(volumeInput.value) || 0;
        tarifInput.value = tarifPerKubik;
        totalInput.value = tarifPerKubik * volume;
    }

    if (jenisTarifSelect && volumeInput) {
        jenisTarifSelect.addEventListener("change", updateTotal);
        volumeInput.addEventListener("input", updateTotal);
    }
});

// Checkbox "Pilih Semua" untuk daftar tagihan
document.querySelectorAll("#checkAll").forEach((checkAllBox) => {
    checkAllBox.addEventListener("change", function () {
        let targetGroup = this.getAttribute("data-target");
        let checkboxes = document.querySelectorAll(`input[data-group="${targetGroup}"]`);
        checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
    });
});
