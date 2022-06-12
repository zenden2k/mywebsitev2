require('./bootstrap');
require('admin-lte');

import Vue from "vue/dist/vue.js";
import VueRouter from "vue-router";
Vue.use(VueRouter)

import Vue2Editor from "vue2-editor";
Vue.use(Vue2Editor);

Vue.config.productionTip = false

//register component
Vue.component('pagination', require('laravel-vue-pagination'));


Vue.filter('truncate', function (value, size) {
    if (!value) return '';
    value = value.toString();

    if (value.length <= size) {
        return value;
    }
    return value.substr(0, size) + '...';
});

import moment from 'moment'

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD.MM.YYYY HH:mm')
    }
});

const routesWithPrefix = (prefix, routes) => {
    return routes.map(route => {
        route.path = `${prefix}${route.path}`

        return route
    })
}

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
const routes = [
    ...routesWithPrefix('/vt', [
        {
            path: '/',
            component: () => import("./components/Dashboard.vue")
        },
        {
            name: "pages",
            path: '/page',
            component: () => import("./components/Page/Pages.vue")
        },
        {
            name: 'addpage',
            path: '/page/add',
            component: () => import("./components/Page/EditPage.vue")
        },
        {
            name: 'editpage',
            path: '/page/edit/:id',
            component: () => import("./components/Page/EditPage.vue")
        },
        {
            name: "comments",
            path: '/comment',
            component: () => import("./components/Comment/Comments.vue")
        },
        {
            name: 'addcomment',
            path: '/comment/add',
            component: () => import("./components/Comment/EditComment.vue")
        },
        {
            name: 'editcomment',
            path: '/comment/edit/:id',
            component: () => import("./components/Comment/EditComment.vue")
        },
        {
            name: "tabs",
            path: '/tab',
            component: () => import("./components/Tab/Tabs.vue")
        },
        {
            name: 'addtab',
            path: '/tab/add',
            component: () => import("./components/Tab/EditTab.vue")
        },
        {
            name: 'edittab',
            path: '/tab/edit/:id',
            component: () => import("./components/Tab/EditTab.vue")
        },
        {
            name: "sidebarblocks",
            path: '/sidebarblock',
            component: () => import("./components/SidebarBlock/SidebarBlocks.vue")
        },
        {
            name: 'addsidebarblock',
            path: '/sidebarblock/add',
            component: () => import("./components/SidebarBlock/EditSidebarBlock.vue")
        },
        {
            name: 'editsidebarblock',
            path: '/sidebarblock/edit/:id',
            component: () => import("./components/SidebarBlock/EditSidebarBlock.vue")
        }
    ])
]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
    routes, // short for `routes: routes
    prefix: "vt",
    mode: 'history',
    scrollBehavior (to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }
})

const app = new Vue({
    router,
    //mode: "history"
}).$mount('#app')
//app.config.globalProperties.$axios = axios;
