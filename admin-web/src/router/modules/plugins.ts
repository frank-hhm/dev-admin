export default [
    {
        path: `/plugins/map`,
        name: `pluginsMap`,
        component: () => import("@/views/plugins/map.vue"),
        meta: {
            auth: false,
            menu_name: "地图",
        },
    },
    {
        path: `/plugins/editor`,
        name: `pluginsEditor`,
        component: () => import("@/views/plugins/editor.vue"),
        meta: {
            auth: false,
            menu_name: "富文本",
        },
    },
]