(function ($) {
    "use strict";

    const revenueDayCtx = document.getElementById("revenueByDayChart");
    const revenueMonthCtx = document.getElementById("revenueByMonthChart");

    const dayGradient = revenueDayCtx
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 350);

    dayGradient.addColorStop(0, "rgba(40,167,69,.30)");
    dayGradient.addColorStop(1, "rgba(40,167,69,0)");

    function formatCurrency(value) {
        return new Intl.NumberFormat("vi-VN").format(value) + " đ";
    }

    new Chart(revenueDayCtx, {
        type: "line",

        data: {
            labels: Object.keys(window.revenueByDay),

            datasets: [
                {
                    label: "Doanh thu",

                    data: Object.values(window.revenueByDay),

                    borderColor: "#28a745",

                    backgroundColor: dayGradient,

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
                            return formatCurrency(context.raw);
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
                        callback(value) {
                            return new Intl.NumberFormat("vi-VN").format(value);
                        },
                    },
                },
            },
        },
    });

    new Chart(revenueMonthCtx, {
        type: "bar",

        data: {
            labels: Object.keys(window.revenueByMonth),

            datasets: [
                {
                    label: "Doanh thu",

                    data: Object.values(window.revenueByMonth),

                    backgroundColor: "rgba(13,110,253,.7)",

                    borderRadius: 8,

                    borderSkipped: false,
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
                            return formatCurrency(context.raw);
                        },
                    },
                },
            },
        },
    });
})(jQuery);
