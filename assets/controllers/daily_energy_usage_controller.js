import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';
import zoomPlugin from 'chartjs-plugin-zoom';

Chart.register(zoomPlugin);

export default class extends Controller {
    static targets = ['canvas'];
    static values = { chart: Object };

    connect() {
        this.renderChart(this.chartValue);

        // Listen for day change
        const daySelect = document.getElementById('daily-usage-date');
        if (daySelect) {
            daySelect.addEventListener('change', () => this.onDayChange());
        }
    }

    renderChart(rawData) {
        if (this.chart) {
            this.chart.destroy();
        }

        const labels = rawData.labels;
        const totalLabels = labels.length;

        // 2 hours = 120 minutes; for 5-min intervals, that’s 24 points
        const showCount = 144;
        const startIdx = Math.max(totalLabels - showCount, 0);
        const minLabel = labels[startIdx];
        const maxLabel = labels[totalLabels - 1];

        const ctx = this.canvasTarget.getContext('2d');
        this.chart = new Chart(ctx, {
            type: 'line',
            data: rawData,
            options: {
                responsive: true,
                scales: {
                    x: {
                        ticks: {
                            callback: function(value) {
                                const label = this.getLabelForValue(value);
                                if (!label) return '';
                                const hour = parseInt(label.substring(0, 2), 10);
                                if (label.endsWith(':00') && hour % 3 === 0) {
                                    return label;
                                }
                                return '';
                            },
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0,
                        },
                        min: minLabel,
                        max: maxLabel,
                        grid: {
                            display: false
                        },
                    },
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false },
                    zoom: {
                        pan: { enabled: true, mode: 'x' },
                        zoom: {
                            wheel: { enabled: true },
                            pinch: { enabled: true },
                            mode: 'x',
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
    }

    onDayChange() {
        const day = document.getElementById('daily-usage-date').value;
        fetch(`/ajax/daily-energy-usage?day=${day}`)
            .then(response => response.json())
            .then(data => {
                this.renderChart(data);
            })
            .catch(() => {
                alert('Could not load chart data for the selected day.');
            });
    }

    disconnect() {
        if (this.chart) {
            this.chart.destroy();
        }
    }

    resetZoom() {
        if (this.chart) {
            this.chart.resetZoom();
        }
    }
}
