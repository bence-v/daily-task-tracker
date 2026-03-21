import Alpine from 'alpinejs';

import { initTaskCompletionHandlers } from './utils.js'

window.Alpine = Alpine;

Alpine.start();

initTaskCompletionHandlers();
