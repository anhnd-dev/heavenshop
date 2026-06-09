(function ($) {
    "use strict";

    const ctx = document.getElementById("monthlyRevenueChart");

    new Chart(ctx, {
        type: "line",

        data: {
            labels: Object.keys(window.monthlyRevenue),

            datasets: [
                {
                    label: "Doanh thu",

                    data: Object.values(window.monthlyRevenue),

                    borderColor: "#28a745",

                    backgroundColor: "rgba(40, 167, 69, .1)",

                    borderWidth: 3,

                    pointRadius: 5,

                    pointHoverRadius: 8,

                    pointBackgroundColor: "#28a745",

                    pointBorderColor: "#fff",

                    pointBorderWidth: 2,

                    tension: 0.4,

                    fill: true,
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
                    backgroundColor: "#1f2937",

                    padding: 12,

                    titleFont: {
                        size: 14,
                    },

                    bodyFont: {
                        size: 13,
                    },

                    callbacks: {
                        label: function (context) {
                            return (
                                new Intl.NumberFormat("vi-VN").format(
                                    context.raw,
                                ) + " đ"
                            );
                        },
                    },
                },
            },

            scales: {
                x: {
                    grid: {
                        display: false,
                    },

                    ticks: {
                        color: "#6c757d",
                    },
                },

                y: {
                    beginAtZero: true,

                    grid: {
                        color: "#f1f3f5",
                    },

                    ticks: {
                        color: "#6c757d",

                        callback: function (value) {
                            return new Intl.NumberFormat("vi-VN").format(value);
                        },
                    },
                },
            },
        },
    });
})(jQuery);
