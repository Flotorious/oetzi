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
        ctx.font = 'bold 16px sans-serif';
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
        chart: Object,
        devices: Array
    };

    connect() {
        const ctx = this.canvasTarget.getContext('2d');
        this.chart = new Chart(ctx, {
            type: 'bar',
            data: this.chartValue,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Daily Energy Usage by Device'
                    }
                },
                onClick: (event, elements) => {
                    if (!elements.length) return;

                    const element = elements[0];
                    const dataset = this.chart.data.datasets[element.datasetIndex];

                    if (dataset.label === 'Unregistered Consumption') {
                        window.location.href = '/profile/user-device'
                    }
                },
                onHover: (event, elements) => {
                    const canvas = this.chart.canvas;
                    if (elements.length && this.chart.data.datasets[elements[0].datasetIndex].label === 'Unregistered Consumption') {
                        canvas.style.cursor = 'pointer';
                    } else {
                        canvas.style.cursor = 'default';
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            },
            plugins: [unregisteredLabelPlugin]
        });
    }
}
