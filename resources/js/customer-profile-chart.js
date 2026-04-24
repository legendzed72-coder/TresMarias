import { Chart, registerables } from 'chart.js';
import { leaf, leafBg, gold, goldBg, muted, grid, fontFamily } from './chart-colors';

Chart.register(...registerables);

document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('chart-data-customer-profile');
    if (!el) return;

    const data = JSON.parse(el.textContent);

    const canvas = document.getElementById('spendingTrendChart');
    if (!canvas) return;

    new Chart(canvas.getContext('2d'), {
        type: 'line',
        data: {
            labels: data.trendLabels,
            datasets: [
                {
                    label: data.labelSpending,
                    data: data.trendSpending,
                    borderColor: leaf,
                    backgroundColor: leafBg,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    yAxisID: 'y',
                },
                {
                    label: data.labelOrders,
                    data: data.trendOrders,
                    borderColor: gold,
                    backgroundColor: goldBg,
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    labels: { color: muted, font: { family: fontFamily, size: 12 }, usePointStyle: true }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    ticks: { color: muted, font: { family: fontFamily, size: 11 }, callback: v => '₱' + v },
                    grid: { color: grid }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: muted, font: { family: fontFamily, size: 11 } },
                    grid: { drawOnChartArea: false }
                },
                x: {
                    ticks: { color: muted, font: { family: fontFamily, size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
});
