// ✅ assets/controllers/energy_chart_controller.js
import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

export default class extends Controller {
    static targets = ['canvas'];
    static values = {
        chart: Object,
        devices: Array
    };

    connect() {
        this.ctx = this.canvasTarget.getContext('2d');
        this.chart = new Chart(this.ctx, {
            type: 'bar',
            data: this.chartValue,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Daily Energy Usage by Device'
                    }
                },
                scales: {
                    x: { stacked: true },
                    y: { stacked: true, beginAtZero: true }
                }
            }
        });
    }
}