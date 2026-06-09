(function ($) {
    "use strict";

    const ctx = document.getElementById("topRevenueChart");

    new Chart(ctx, {
        type: "bar",

        data: {
            labels: window.topRevenueProducts.map((x) => x.product_name),

            datasets: [
                {
                    label: "Doanh thu",

                    data: window.topRevenueProducts.map((x) => x.revenue),

                    backgroundColor: "rgba(40,167,69,.75)",

                    borderRadius: 10,

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
})(jQuery);
