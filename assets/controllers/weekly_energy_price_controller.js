import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

export default class extends Controller {
    static targets = ['canvas'];
    static values  = { chart: Object };

    connect() {
        const ctx = this.canvasTarget.getContext('2d');
        this.chart = new Chart(ctx, {
            type: 'bar',
            data: this.chartValue,
            options: {
                responsive: true,
                plugins: {
                    tooltip: { mode: 'index', intersect: false },
                    title: {
                        display: true,
                        text: 'Weekly energy price',
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
                        title: { display: true, text: 'Cost (€)' }
                    }
                }
            }
        });
    }
}
