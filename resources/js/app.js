import './bootstrap.js';
import { createApp } from 'vue';
import router from './src/router.js';
import App from './src/App.vue';

createApp(App).use(router).mount('#app');
