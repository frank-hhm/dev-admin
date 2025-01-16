export default [
    // 登录
    {
        path: '/login',
        name: 'login',
        meta: {
            login:false,
            menu_name: '登录'
        },
        component: () => import('@/views/login/index.vue')
    },
    // {
    //     path: "/:pathMatch(.*)*", // Must put the 'ErrorPage' route at the end, 必须将 'ErrorPage' 路由放在最后
    //     redirect: "/404",
    //     name: "ErrorPage",
    //     meta: {
    //     },
    // },
    // {
    //     path: '/404',
    //     name: '404',
    //     meta: {
    //         title: '404'
    //     },
    //     component: () => import('@/views/404.vue')
    // },
]