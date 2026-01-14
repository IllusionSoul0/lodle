import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Classic from '../views/Classic.vue'
import Login from '../views/Login.vue'
import Stats from '@/views/Stats.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: Home },
    { path: '/classic', component: Classic },
    { path: '/login', component: Login },
    { path: '/stats', component: Stats }
  ]
})

export default router