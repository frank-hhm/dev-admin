import BasicLayout from "@/components/layouts/Layout.vue";

import system from './system'
import media from './media'
import plugins from './plugins'

export default [{
    path: '/',
    name: '/',
    header: '/',
    redirect: {
        path: `/system/config`
    },
    component: BasicLayout,
    children: [
        {
            path: '/index',
            name: '/index',
            meta: {
                auth: false,
                menu_name: "首页",
            },
            component: () => import('@/views/index/index.vue')
        },
        ...system,
        ...media,
        ...plugins
    ]
}]
