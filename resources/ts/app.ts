import './bootstrap';

import { createApp } from 'vue'

import UrlShortener from '../views/components/UrlShortener.vue';
import '../css/app.css'

createApp(UrlShortener).mount('#app')
