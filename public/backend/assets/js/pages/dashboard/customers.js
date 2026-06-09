(function ($) {
    "use strict";

    const chartCanvas = document.getElementById("customerGrowthChart");

    const gradient = chartCanvas
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 350);

    gradient.addColorStop(0, "rgba(111,66,193,.25)");

    gradient.addColorStop(1, "rgba(111,66,193,0)");

    new Chart(chartCanvas, {
        type: "line",

        data: {
            labels: Object.keys(window.customerGrowth),

            datasets: [
                {
                    label: "Khách hàng mới",

                    data: Object.values(window.customerGrowth),

                    borderColor: "#6f42c1",

                    backgroundColor: gradient,

                    fill: true,

                    tension: 0.4,

                    borderWidth: 3,

                    pointRadius: 4,

                    pointHoverRadius: 7,
                },
            ],
        },

        options: {
            responsive: true,

            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false,
                },

                tooltip: {
                    callbacks: {
                        label(context) {
                            return context.raw + " khách hàng";
                        },
                    },
                },
            },

            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                },

                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0,
                    },
                },
            },
        },
    });
})(jQuery);
