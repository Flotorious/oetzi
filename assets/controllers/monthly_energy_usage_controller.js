import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

export default class extends Controller {
    static targets = ['canvas'];
    static values = { chart: Object };

    connect() {
        const ctx = this.canvasTarget.getContext('2d');
        this.chart = new Chart(ctx, {
            type: 'line',
            data: this.chartValue,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    title: {
                        display: true,
                        text: 'Monthly Energy Consumption'
                    }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Month' },
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
