// assets/controllers/line_chart_controller.js
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
                scales: {
                    x: {
                        title: { display: true, text: 'Time (HH:MM)' }
                    },
                    y: {
                        title: { display: true, text: 'Consumption Δ (kWh)' },
                        beginAtZero: true
                    }
                }
            }
        });
    }
}
