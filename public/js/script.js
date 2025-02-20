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
