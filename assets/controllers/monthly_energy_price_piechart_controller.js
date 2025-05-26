import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

export default class extends Controller {
    static targets = ['canvas'];
    static values = {
        chart: Object
    };

    connect() {
        this.renderChart(this.chartValue);

        // Listen for month change
        const monthSelect = document.getElementById('piechart-month');
        if (monthSelect) {
            monthSelect.addEventListener('change', () => this.onMonthChange());
        }
    }

    renderChart(rawData) {
        if (this.chart) {
            this.chart.destroy();
        }
        const labels = rawData.datasets.map(ds => ds.label);
        const data = rawData.datasets.map(ds => ds.data[0] || 0);
        const backgroundColor = rawData.datasets.map(ds => ds.backgroundColor);
        const borderColor = rawData.datasets.map(ds => ds.borderColor);
        const total = data.reduce((sum, val) => sum + val, 0);

        const transformedChartData = {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        };

        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw(chart) {
                const { width, height, ctx } = chart;
                ctx.save();
                ctx.font = 'bold 20px sans-serif';
                ctx.fillStyle = '#333';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(`${total.toFixed(2)} €`, width / 2, height / 2);
                ctx.restore();
            }
        };

        this.chart = new Chart(this.canvasTarget.getContext('2d'), {
            type: 'doughnut',
            data: transformedChartData,
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            },
            plugins: [centerTextPlugin]
        });
    }

    onMonthChange() {
        const month = document.getElementById('piechart-month').value;
        const startDate = `${month}-01`;
        const endDateObj = new Date(startDate);
        endDateObj.setMonth(endDateObj.getMonth() + 1);
        endDateObj.setDate(0);
        const endDate = endDateObj.toISOString().split('T')[0];

        fetch(`/ajax/monthly-energy-price?start=${startDate}&end=${endDate}`)
            .then(response => response.json())
            .then(data => {
                this.renderChart(data);
            })
            .catch(() => {
                alert('Could not load chart data for the selected month.');
            });
    }
}
