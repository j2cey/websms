import VueRouter from 'vue-router';


let routes = [
    {
        path: '/dashboard',
        component: require('./views/dashboard').default
    },
    {
        path: '/smscampaigns',
        component: require('./views/smscampaigns/index').default
    }
];


export default new VueRouter({
    base: '/',
    mode: 'history',
    routes,
    linkActiveClass: 'active'
});
