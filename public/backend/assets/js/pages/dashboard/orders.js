(function ($) {
    "use strict";

    const orderStatusChart = document.getElementById("orderStatusChart");
    const orderTrendChart = document.getElementById("orderTrendChart");

    const statusColors = {
        pending: "#ffc107",
        confirmed: "#0dcaf0",
        shipping: "#0d6efd",
        delivered: "#28a745",
        cancelled: "#dc3545",
        returned: "#6c757d",
    };

    const trendGradient = orderTrendChart
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 350);

    trendGradient.addColorStop(0, "rgba(13,110,253,.25)");

    trendGradient.addColorStop(1, "rgba(13,110,253,0)");

    new Chart(orderStatusChart, {
        type: "doughnut",

        data: {
            labels: Object.keys(window.orderStatus),

            datasets: [
                {
                    data: Object.values(window.orderStatus),

                    backgroundColor: Object.keys(window.orderStatus).map(
                        (status) => statusColors[status] || "#adb5bd",
                    ),

                    borderWidth: 0,
                },
            ],
        },

        options: {
            responsive: true,

            maintainAspectRatio: false,

            cutout: "65%",

            plugins: {
                legend: {
                    position: "bottom",
                },

                tooltip: {
                    callbacks: {
                        label(context) {
                            return (
                                context.label +
                                ": " +
                                new Intl.NumberFormat("vi-VN").format(
                                    context.raw,
                                )
                            );
                        },
                    },
                },
            },
        },
    });

    new Chart(orderTrendChart, {
        type: "line",

        data: {
            labels: Object.keys(window.orderTrend),

            datasets: [
                {
                    label: "Đơn hàng",

                    data: Object.values(window.orderTrend),

                    borderColor: "#0d6efd",

                    backgroundColor: trendGradient,

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
                            return (
                                new Intl.NumberFormat("vi-VN").format(
                                    context.raw,
                                ) + " đơn"
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
