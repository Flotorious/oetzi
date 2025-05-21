// assets/controllers/line_chart_controller.js
import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';
import zoomPlugin from 'chartjs-plugin-zoom';

Chart.register(zoomPlugin);

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
                },
                plugins: {
                    zoom: {
                        pan: {
                            enabled: true,       // enable panning
                            mode: 'x',           // x direction
                        },
                        zoom: {
                            wheel: {
                                enabled: true,     // enable wheel zoom
                            },
                            pinch: {
                                enabled: true      // enable pinch zoom
                            },
                            mode: 'x',           // zoom in x direction
                        }
                    }
                }
            }
        });
    }

    disconnect() {
        if (this.chartInstance) {
            this.chartInstance.destroy();
        }
    }

    resetZoom() {
        if (this.chartInstance) {
            this.chartInstance.resetZoom();
        }
    }
}
