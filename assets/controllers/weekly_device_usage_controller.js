import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

const unregisteredLabelPlugin = {
    id: 'unregisteredLabelPlugin',
    afterDatasetsDraw(chart) {
        const {ctx, data, chartArea: {top, bottom}, scales: {x, y}} = chart;

        const datasetIndex = data.datasets.findIndex(ds => ds.label === 'Unregistered Consumption');
        if (datasetIndex === -1) return;

        const dataset = chart.getDatasetMeta(datasetIndex);
        ctx.save();
        ctx.fillStyle = 'black';
        ctx.font = '16px sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        dataset.data.forEach((bar, index) => {
            if (!bar) return;

            const value = data.datasets[datasetIndex].data[index];
            if (!value || value === 0) return;

            const xCenter = bar.x;
            const yCenter = bar.y + bar.height / 2;

            ctx.fillText('+', xCenter, yCenter);
        });

        ctx.restore();
    }
};

export default class extends Controller {
    static targets = ['canvas'];
    static values = {
        chart: Object
    };

    connect() {
        this.renderChart(this.chartValue);
        document.getElementById('device-usage-filter-btn').addEventListener('click', () => this.onFilter());
    }

    async onFilter() {
        const start = document.getElementById('from-date').value;
        const end = document.getElementById('to-date').value;
        if (!start || !end) return;

        const params = new URLSearchParams({ start, end });
        const response = await fetch(`/ajax/weekly-device-usage?${params.toString()}`);
        const data = await response.json();

        this.renderChart(data);
    }

    renderChart(chartData) {
        if (this.chart) {
            this.chart.destroy();
        }
        this.chart = new Chart(this.canvasTarget.getContext('2d'), {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        filter: function(context) {
                            return context.parsed.y > 0;
                        },
                        itemSort: function(a, b) {
                            return b.parsed.y - a.parsed.y;
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                // Show both the value and the unit
                                let value = context.parsed.y;
                                let unit = "kWh";
                                if (value == null) return label;
                                return `${label}: ${value} ${unit}`;
                            },
                        },
                    },
                },
                onClick: (event, elements) => {
                    if (!elements.length) return;
                    const element = elements[0];
                    const dataset = this.chart.data.datasets[element.datasetIndex];

                    if (dataset.label === 'Unregistered Consumption') {
                        window.location.href = '/profile/user-device';
                    }
                },
                onHover: (event, elements) => {
                    const canvas = this.chart.canvas;
                    if (
                        elements.length &&
                        this.chart.data.datasets[elements[0].datasetIndex].label === 'Unregistered Consumption'
                    ) {
                        canvas.style.cursor = 'pointer';
                    } else {
                        canvas.style.cursor = 'default';
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        title: { display: true, text: 'Date' }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: { display: true, text: 'kWh' }
                    }
                }
            },
            plugins: [unregisteredLabelPlugin]
        });
    }
}