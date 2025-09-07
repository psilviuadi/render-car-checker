import './bootstrap';
import { createApp } from 'vue'
import CarChecker from './components/CarChecker.vue';

const app = createApp({});
app.component('car-checker', CarChecker);
app.mount('#app');