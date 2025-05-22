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
    static values = {
        chart: Object
    };

    connect() {
        if (!this.hasChartValue) {
            console.warn("Missing chartValue");
            return;
        }

        const ctx = this.canvasTarget.getContext('2d');
        const { priceRateBands = [], ...chartData } = this.chartValue;

        this.chart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Energy Usage per day'
                    },
                    priceRateBackground: {
                        bands: priceRateBands
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        type: 'category',
                        title: { display: true, text: 'Time of Day' }
                    },
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
