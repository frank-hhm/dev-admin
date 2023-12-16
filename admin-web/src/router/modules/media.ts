export default [
    {
        path: `/media/list`,
        name: `mediaList`,
        component: () => import("@/views/media/list.vue"),
        meta: {
            auth: 'media-list',
            menu_name: "素材库",
        },
    },
]