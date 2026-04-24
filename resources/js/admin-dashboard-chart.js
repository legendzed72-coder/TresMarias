import { Chart, registerables } from 'chart.js';
import { bark, barkBg, gold, goldBg, muted, grid, fontFamily, fontOpts } from './chart-colors';

Chart.register(...registerables);

document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('chart-data-dashboard');
    if (!el) return;

    const data = JSON.parse(el.textContent);

    new Chart(document.getElementById('salesChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: data.chartLabels,
            datasets: [
                {
                    label: data.labelSales,
                    data: data.chartSales,
                    backgroundColor: barkBg,
                    borderColor: bark,
                    borderWidth: 2,
                    borderRadius: 8,
                    yAxisID: 'y',
                },
                {
                    label: data.labelOrders,
                    data: data.chartOrders,
                    type: 'line',
                    borderColor: gold,
                    backgroundColor: goldBg,
                    borderWidth: 2,
                    pointBackgroundColor: gold,
                    pointRadius: 4,
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
                legend: {
                    labels: { font: fontOpts, color: muted }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: fontFamily }, color: muted }
                },
                y: {
                    position: 'left',
                    grid: { color: grid },
                    ticks: {
                        font: { family: fontFamily }, color: muted,
                        callback: function(v) { return '₱' + v.toLocaleString(); }
                    }
                },
                y1: {
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: {
                        font: { family: fontFamily }, color: muted,
                        stepSize: 1
                    }
                }
            }
        }
    });
});
