import { Chart, registerables } from 'chart.js';
import {
    bark, barkBg, gold, goldBg, leaf, leafBg, muted, grid,
    categoryColors, categoryBgColors,
    fontFamily, fontOpts, sharedScaleX, sharedScaleY, sharedScaleY1
} from './chart-colors';

Chart.register(...registerables);

document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('chart-data-chartsale');
    if (!el) return;

    const data = JSON.parse(el.textContent);

    // --- Period data sets ---
    const periodData = {
        daily:   { labels: data.dailyLabels,   sales: data.dailySales,   orders: data.dailyOrders },
        weekly:  { labels: data.weeklyLabels,  sales: data.weeklySales,  orders: data.weeklyOrders },
        monthly: { labels: data.monthlyLabels, sales: data.monthlySales, orders: data.monthlyOrders },
    };

    // --- Sales Trend Chart ---
    const salesTrendChart = new Chart(document.getElementById('salesTrendChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: periodData.daily.labels,
            datasets: [
                {
                    label: data.labelSales,
                    data: periodData.daily.sales,
                    backgroundColor: barkBg,
                    borderColor: bark,
                    borderWidth: 2,
                    borderRadius: 8,
                    yAxisID: 'y',
                },
                {
                    label: data.labelOrders,
                    data: periodData.daily.orders,
                    type: 'line',
                    borderColor: gold,
                    backgroundColor: goldBg,
                    borderWidth: 2,
                    pointBackgroundColor: gold,
                    pointRadius: 3,
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { labels: { font: fontOpts, color: muted } }
            },
            scales: { x: sharedScaleX, y: sharedScaleY, y1: sharedScaleY1 }
        }
    });

    // Period toggle function (exposed globally for Alpine)
    window.switchPeriod = function(period) {
        const d = periodData[period];
        salesTrendChart.data.labels = d.labels;
        salesTrendChart.data.datasets[0].data = d.sales;
        salesTrendChart.data.datasets[1].data = d.orders;
        salesTrendChart.update();
    };

    // --- Category Doughnut Chart ---
    if (data.categoryLabels.length > 0) {
        new Chart(document.getElementById('categoryChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: data.categoryLabels,
                datasets: [{
                    data: data.categorySales,
                    backgroundColor: categoryBgColors.slice(0, data.categoryLabels.length),
                    borderColor: categoryColors.slice(0, data.categoryLabels.length),
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: fontOpts, color: muted, padding: 16 }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ctx.label + ': ₱' + ctx.parsed.toLocaleString(undefined, { minimumFractionDigits: 2 });
                            }
                        }
                    }
                }
            }
        });
    }

    // --- Order Type Doughnut Chart ---
    if (data.posSales > 0 || data.preorderSales > 0) {
        new Chart(document.getElementById('orderTypeChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: [data.labelPos, data.labelPreorders],
                datasets: [{
                    data: [data.posSales, data.preorderSales],
                    backgroundColor: [barkBg, goldBg],
                    borderColor: [bark, gold],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: fontOpts, color: muted, padding: 16 }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ctx.label + ': ₱' + ctx.parsed.toLocaleString(undefined, { minimumFractionDigits: 2 });
                            }
                        }
                    }
                }
            }
        });
    }

    // --- Top Products Horizontal Bar Chart ---
    if (data.topProductLabels.length > 0) {
        new Chart(document.getElementById('topProductsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: data.topProductLabels,
                datasets: [
                    {
                        label: data.labelRevenue,
                        data: data.topProductRevenue,
                        backgroundColor: barkBg,
                        borderColor: bark,
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        label: data.labelQtySold,
                        data: data.topProductQty,
                        type: 'bar',
                        backgroundColor: leafBg,
                        borderColor: leaf,
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { labels: { font: fontOpts, color: muted } }
                },
                scales: {
                    x: {
                        position: 'top',
                        grid: { color: grid },
                        ticks: {
                            font: { family: fontFamily }, color: muted,
                            callback: function(v) { return '₱' + v.toLocaleString(); }
                        }
                    },
                    x1: {
                        position: 'bottom',
                        grid: { drawOnChartArea: false },
                        ticks: { font: { family: fontFamily }, color: muted }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { family: fontFamily, weight: '600' }, color: muted }
                    },
                    y1: { display: false }
                }
            }
        });
    }

    // --- Daily Orders Line Chart ---
    new Chart(document.getElementById('ordersChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: data.dailyLabels,
            datasets: [{
                label: data.labelOrders,
                data: data.dailyOrders,
                borderColor: leaf,
                backgroundColor: leafBg,
                borderWidth: 2,
                pointBackgroundColor: leaf,
                pointRadius: 3,
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { font: fontOpts, color: muted } }
            },
            scales: {
                x: sharedScaleX,
                y: {
                    grid: { color: grid },
                    ticks: { font: { family: fontFamily }, color: muted, stepSize: 1 }
                }
            }
        }
    });
});
