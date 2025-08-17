import { createRouter, createWebHistory } from 'vue-router';
import RegisterView from './components/RegistrationView.vue';
import LuckyView from './components/LuckyView.vue';
import NotFoundView from './components/NotFoundView.vue';

const routes = [
    { path: '/', component: RegisterView },
    { path: '/lucky/:token', component: LuckyView, props: true },
    { path: '/404', component: NotFoundView },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
