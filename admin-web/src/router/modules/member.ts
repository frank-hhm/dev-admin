export default [
    {
        path: `/member/list`,
        name: `memberList`,
        component: () => import("@/views/member/member/list.vue"),
        meta: {
            auth: "member-list",
            menu_name: "用户列表",
        },
    }
]