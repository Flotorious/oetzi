import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

const priceRateBackgroundPlugin = {
    id: 'priceRateBackground',
    beforeDatasetsDraw(chart) {
        const { ctx, chartArea: { top, bottom }, scales: { x }, data } = chart;
        const bands = chart.options.plugins.priceRateBackground?.bands || [];
        const labels = data.labels;
        ctx.save();
        for (const band of bands) {
            const startLabel = labels.find(label => label >= band.start) || labels[0];
            const endLabel = labels.find(label => label >= band.end) || labels[labels.length - 1];
            const xStart = x.getPixelForValue(startLabel);
            const xEnd = x.getPixelForValue(endLabel);
            ctx.fillStyle = band.color || 'rgba(0, 0, 0, 0.1)';
            ctx.fillRect(xStart, top, xEnd - xStart, bottom - top);
        }
        ctx.restore();
    }
};

export default class extends Controller {
    static targets = ['canvas'];
    static values = { chart: Object };

    connect() {
        this.renderChart(this.chartValue);

        // Listen for both dropdown and date input changes
        const selectInput = document.getElementById('daily-usage-day');
        const dateInput = document.getElementById('daily-usage-date');

        if (selectInput) {
            selectInput.addEventListener('change', () => this.onDayChange(selectInput.value));
        }
        if (dateInput) {
            dateInput.addEventListener('change', () => this.onDayChange(dateInput.value));
        }
    }

    async onDayChange(day) {
        if (!day) return;
        const params = new URLSearchParams({ day });
        const response = await fetch(`/ajax/daily-device-usage?${params.toString()}`);
        const data = await response.json();
        this.renderChart(data);
    }

    renderChart(chartData) {
        if (this.chart) {
            this.chart.destroy();
        }
        const { priceRateBands = [], ...plainChartData } = chartData;
        this.chart = new Chart(this.canvasTarget.getContext('2d'), {
            type: 'line',
            data: plainChartData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    priceRateBackground: { bands: priceRateBands }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: { type: 'category' },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'kWh / 5 min' }
                    }
                }
            },
            plugins: [priceRateBackgroundPlugin]
        });
    }
}
