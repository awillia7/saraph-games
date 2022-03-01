import Vue from "vue";
import axios from "axios";
import store from "./store";

import VueRouter from "vue-router";
Vue.use(VueRouter);

import HomePage from '../components/HomePage.vue';
import CardPage from '../components/CardPage.vue';
import LexiconPage from '../components/LexiconPage.vue';
import LoginPage from '../components/LoginPage.vue';
import PostPage from '../components/PostPage.vue';

let router = new VueRouter({
    mode: "history",
    routes: [
        { path: '/', component: HomePage, name: 'home' },
        { path: '/posts/:post', component: PostPage, name: 'post' },
        { path: '/admin/posts/new', component: PostPage, name: 'newPost' },
        { path: '/lexicon/cards', component: LexiconPage, name: 'lexicon-cards' },
        { path: '/lexicon/cards/:card', component: CardPage, name: 'lexicon-card' },
        { path: '/lexicon', component: LexiconPage, name: 'lexicon-home' },
        { path: '/login', component: LoginPage, name: 'login' }
    ],
    scrollBehavior (to, from, savePosition) {
        return { x: 0, y: 0 }
    }
});

router.beforeEach((to, from, next) => {
    let serverData = JSON.parse(window.saraphgames_server_data);
    if (
        (to.name === 'lexicon-home'
        && store.getters.getLastGlobalSearch(to.params)
        ) || to.name === 'login'
    ) 
    {
        next();
    }
    else if (!serverData.path || to.path !== serverData.path) {
        axios.get(`/api${to.path}`).then(({data}) => {
            store.commit('addData', {route: to.name, data});
            next();
        });
    } else {
        store.commit('addData', {route: to.name, data: serverData});
        next();
    }
});

export default router;