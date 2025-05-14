document.addEventListener("DOMContentLoaded", function () {
    // Debugging
    console.log("Jenis Labels:", window.labelsJenis);
    console.log("Jenis Data:", window.dataJenis);

    // Chart Pendapatan per Bulan
    const ctxPendapatan = document
        .getElementById("chartPendapatan")
        .getContext("2d");
    new Chart(ctxPendapatan, {
        type: "bar",
        data: {
            labels: window.labelsBulan,
            datasets: [
                {
                    label: "Pendapatan (Rp)",
                    data: window.dataBulan,
                    backgroundColor: "rgba(54, 162, 235, 0.7)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1,
                    borderRadius: 5,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return "Rp " + value.toLocaleString("id-ID");
                        },
                    },
                },
            },
        },
    });

    // Pie/Doughnut Chart Pendapatan per Jenis
    new Chart(document.getElementById("chartJenisRetribusi"), {
        type: "doughnut",
        data: {
            labels: window.labelsJenis,
            datasets: [
                {
                    label: "Total Pembayaran",
                    data: window.dataJenis,
                    backgroundColor: [
                        "#4e73df",
                        "#1cc88a",
                        "#f6c23e",
                        "#e74a3b",
                    ],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                },
            },
        },
    });

    // Line Chart Jumlah Warga Membayar
    new Chart(document.getElementById("chartWargaBayar"), {
        type: "line",
        data: {
            labels: window.labelsWarga,
            datasets: [
                {
                    label: "Jumlah Warga",
                    data: window.dataWarga,
                    backgroundColor: "#28a745",
                    borderColor: "#28a745",
                    fill: false,
                    tension: 0.3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "Jumlah Warga",
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });
});

document.addEventListener("DOMContentLoaded", function () {
    if (window.kelurahanLabels.length > 0 && window.kelurahanData.length > 0) {
        const ctx = document.getElementById("chartKelurahan").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: window.kelurahanLabels,
                datasets: [
                    {
                        label: "Jumlah Warga",
                        data: window.kelurahanData,
                        backgroundColor: "#198754",
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.parsed.y} warga`,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: "Jumlah Warga" },
                    },
                    x: {
                        title: { display: true, text: "Kelurahan" },
                    },
                },
            },
        });
    }
});
