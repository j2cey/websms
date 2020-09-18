import VueRouter from 'vue-router';


let routes = [
    {
        name: 'dashboard',
        path: '/dashboard',
        component: require('./views/dashboard').default
    },
    {
        name: 'smscampaigns.index',
        path: '/smscampaigns',
        component: require('./views/smscampaigns/index').default
    },
    {
        name: 'smscampaigns.create',
        path: '/smscampaigns/create',
        component: require('./views/smscampaigns/create').default
    }
];

export default new VueRouter({
    base: '/',
    mode: 'history',
    routes,
    linkActiveClass: 'active'
});
