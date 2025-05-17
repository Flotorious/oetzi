import { startStimulusApp } from '@symfony/stimulus-bridge';
import EnergyChartController from './energy_chart_controller.js';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp();

app.register('energy-chart', EnergyChartController);