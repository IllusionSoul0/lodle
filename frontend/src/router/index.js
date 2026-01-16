import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Classic from '../views/Classic.vue'
import Login from '../views/Login.vue'
import Stats from '@/views/Stats.vue'
import Quote from '@/views/Quote.vue'
import Splash from '@/views/Splash.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: Home },
    { path: '/classic', component: Classic },
    { path: '/quote', component: Quote },
    { path: '/splash', component: Splash },
    { path: '/login', component: Login },
    { path: '/stats', component: Stats }
  ]
})

export default router