import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

export default class extends Controller {
    static targets = ['canvas'];
    static values = { chart: Object };

    connect() {
        this.renderChart(this.chartValue);

        // Attach event listeners
        document.getElementById('filter-btn').addEventListener('click', () => this.onFilter());
    }

    async onFilter() {
        const start = document.getElementById('start-date').value;
        const end = document.getElementById('end-date').value;
        if (!start || !end) return;

        const params = new URLSearchParams({ start, end });
        const response = await fetch(`/ajax/monthly-energy-usage?${params.toString()}`);
        const data = await response.json();

        this.renderChart(data);
    }

    renderChart(chartData) {
        if (this.chart) {
            this.chart.destroy();
        }
        this.chart = new Chart(this.canvasTarget.getContext('2d'), {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 0,
                            autoSkip: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Consumption (kWh)' }
                    }
                }
            }
        });
    }
}
