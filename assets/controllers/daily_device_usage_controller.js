import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

export default class extends Controller {
    static targets = ['canvas'];
    static values = {
        chart: Object
    };

    connect() {
        const ctx = this.canvasTarget.getContext('2d');
        this.chart = new Chart(ctx, {
            type: 'line',
            data: this.chartValue,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Energy Usage per day'
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Time of Day' }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'kWh / 5 min' }
                    }
                }
            }
        });
    }
}