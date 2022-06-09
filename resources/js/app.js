require('./bootstrap');
require('admin-lte');

/*axios.get('/api/page')
    .then(function (response) {
        // handle success
        console.log(response);
    });*/

import Vue from "vue/dist/vue.js";
import VueRouter from "vue-router";
Vue.use(VueRouter)

Vue.config.productionTip = false

//import LaravelVuePagination from 'laravel-vue-pagination';

//register component
//Vue.component('Pagination',LaravelVuePagination);



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
    ...routesWithPrefix('/admin', [
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
        }
    ])
]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
    routes, // short for `routes: routes
    prefix: "admin",
    mode: 'history',// `
})

const app = new Vue({
    router,
    //mode: "history"
}).$mount('#app')
//app.config.globalProperties.$axios = axios;
