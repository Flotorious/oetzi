import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';
import zoomPlugin from 'chartjs-plugin-zoom';

Chart.register(zoomPlugin);

const priceRateBackgroundPlugin = {
    id: 'priceRateBackground',
    beforeDatasetsDraw(chart) {
        const { ctx, chartArea: { top, bottom }, scales: { x }, data } = chart;
        const bands = chart.options.plugins.priceRateBackground?.bands || [];
        const labels = data.labels;
        ctx.save();
        for (const band of bands) {
            const startLabel = labels.find(label => label >= band.start) || labels[0];
            const endLabel = labels.find(label => label >= band.end) || labels[labels.length - 1];
            const xStart = x.getPixelForValue(startLabel);
            const xEnd = x.getPixelForValue(endLabel);
            ctx.fillStyle = band.color || 'rgba(0, 0, 0, 0.1)';
            ctx.fillRect(xStart, top, xEnd - xStart, bottom - top);
        }
        ctx.restore();
    }
};

export default class extends Controller {
    static targets = ['canvas'];
    static values = { chart: Object };

    connect() {
        this.renderChart(this.chartValue);

        // Date change
        const dateInput = document.getElementById('daily-usage-date');
        if (dateInput) {
            dateInput.addEventListener('change', () => this.onDayChange(dateInput.value));
        }
        // Reset zoom
        document.getElementById('reset-zoom')?.addEventListener('click', () => {
            this.chart?.resetZoom();
        });
    }

    async onDayChange(day) {
        if (!day) return;
        const params = new URLSearchParams({ day });
        const response = await fetch(`/ajax/daily-device-usage?${params.toString()}`);
        const data = await response.json();
        this.renderChart(data);
    }

    renderChart(chartData) {
        if (this.chart) {
            this.chart.destroy();
        }
        const labels = chartData.labels;
        const totalLabels = labels.length;

        // Last 6 hours = 72 intervals (5 min each)
        const showCount = 170;
        const minIdx = Math.max(totalLabels - showCount, 0);
        const minLabel = labels[minIdx];
        const maxLabel = labels[totalLabels - 1];

        const { priceRateBands = [], ...plainChartData } = chartData;
        this.chart = new Chart(this.canvasTarget.getContext('2d'), {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
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
                    priceRateBackground: {
                        bands: priceRateBands
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        filter: function(context) {
                            return context.parsed.y > 0;
                        },
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label || '';
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        type: 'category',
                        min: minLabel,
                        max: maxLabel,
                        ticks: {
                            callback: function(value) {
                                const label = this.getLabelForValue(value);
                                if (!label) return '';
                                const hour = parseInt(label.slice(11, 13), 10);
                                if (label.endsWith(':00') && hour % 2 === 0) {
                                    return label.slice(11); // "HH:mm"
                                }
                                return '';
                            },
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0,
                        },
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'kWh / 10 min' }
                    }
                }
            },
            plugins: [zoomPlugin, priceRateBackgroundPlugin]
        });
        this.renderPriceRateLegend(chartData.priceRateBands || []);
    }

    renderPriceRateLegend(bands) {
        const legendContainer = document.getElementById('price-rate-legend');
        if (!legendContainer) return;

        legendContainer.innerHTML = ''; // Clear previous legend

        if (bands.length === 0) return;

        let legendHTML = '<div style="display:flex; flex-wrap:wrap; align-items:center;">';
        legendHTML += '<div style="margin-right:12px;">Price Rate Periods:</div>';

        bands.forEach(band => {
            legendHTML += `
            <span style="
                display: flex;
                align-items: center; 
                margin-right: 16px;
                font-size: 14px;
            ">
                <span style="
                    display:inline-block;
                    width: 20px;
                    height: 12px;
                    background: ${band.color};
                    border-radius: 3px;
                    margin-right: 6px;
                    border: 1px solid #999;
                "></span>
                <span>${band.start} - ${band.end}</span>
            </span>
        `;
        });

        legendHTML += '</div>';
        legendContainer.innerHTML = legendHTML;
    }
}