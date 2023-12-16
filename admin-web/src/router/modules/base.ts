import BasicLayout from "@/components/layouts/Layout.vue";

import system from './system'
import media from './media'

export default [{
    path: '/',
    name: '/',
    header: '/',
    redirect: {
        path: `/index`
    },
    component: BasicLayout,
    children: [
        {
            path: '/index',
            name: '/index',
            meta: {
                auth: '/index',
                menu_name: "首页",
            },
            component: () => import('@/views/index/index.vue')
        },
        ...system,
        ...media
    ]
}]
