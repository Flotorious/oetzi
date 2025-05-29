import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

export default class extends Controller {
    static targets = ['canvas'];
    static values = {
        chart: Object,
        periodType: String
    };

    connect() {
        this.renderChart(this.chartValue);
        document.getElementById('filter-btn').addEventListener('click', () => this.onFilter());
    }

    async onFilter() {
        const start = document.getElementById('start-date').value;
        const end = document.getElementById('end-date').value;
        const period = this.hasPeriodTypeValue ? this.periodTypeValue : 'month';

        const params = new URLSearchParams({
            start, end, periodType: period
        });
        const response = await fetch(`/ajax/period-energy-price?${params.toString()}`);
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
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,

                        itemSort: function(a, b) {
                            return b.parsed.y - a.parsed.y;
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                // Show both the value and the unit
                                let value = context.parsed.y;
                                let unit = "€";
                                if (value == null) return label;
                                return `${label}: ${unit} ${value}`;
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: { display: true, text: 'Cost (€)' }
                    }
                }
            }
        });
    }
}
