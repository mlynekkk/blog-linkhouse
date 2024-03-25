import './bootstrap';

import { createApp } from 'vue'

import app from './components/app.vue'

import route from './router/index.js'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

createApp(app).use(route).mount('#app')
