import { startStimulusApp } from '@symfony/stimulus-bridge';
import WeeklyDeviceUsageController from './weekly_device_usage_controller.js';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp();

app.register('weekly-device-usage', WeeklyDeviceUsageController);