import router from "@/router"
import NProgress from "nprogress"
import "nprogress/nprogress.css"
import { getToken, setDocumentTitle } from "@/utils"
import { useAdminStoreHook, usePermissionStoreHook, useMenusStoreHook, useAppStoreHook } from "@/store"

NProgress.configure({
    showSpinner: false,
})

router.beforeEach(async (to: any, from: any, next: any) => {
    NProgress.start()
    // 判断是否需要登录才可以进入
    const token = getToken()
    if (to.matched.some((_: any) => _.meta.login !== false) || token) {
        const userStore = useAdminStoreHook()
        const useMenusStore = useMenusStoreHook();
        const permissionStore = usePermissionStoreHook();
        const roleAction = permissionStore.roleAction
        try {
            if (token && token !== 'undefined') {
                if (to.path === '/login') {
                    // 如果已经登录，并准备进入 Login 页面，则重定向到主页
                    next({ path: "/" })
                    NProgress.done()
                } else if (useMenusStore.menus && ((typeof roleAction !== 'number' && roleAction.indexOf(to.meta.auth) > -1) || to.meta.auth === false || roleAction === -1)) {
                    next()
                } else {
                }
            } else {
                next({
                    path: '/login',
                    query: {
                        redirect: to.fullPath
                    },
                    replace: true
                })
                userStore.resetToken()
                NProgress.done()
            }
            // 确保添加路由已完成
        } catch (err: any) {
            // 过程中发生任何错误，都直接重置 Token，并重定向到登录页面
            userStore.resetToken()
            next("/login")
            NProgress.done()
        }
    } else {
        next()
    }
})

router.afterEach((to: any, from: any, next: any) => {
    setDocumentTitle(to?.meta?.menu_name);
    NProgress.done()
})
