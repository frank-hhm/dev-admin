import { ref } from "vue"
import store from "@/store"
import { type RouteRecordRaw } from "vue-router"
import { constantRoutes } from "@/router/modules";
import { defineStore } from "pinia"
import { getCacheRoleAction, setCacheRoleAction } from "@/utils";

export const usePermissionStore = defineStore("permission", () => {
    const routes = ref<RouteRecordRaw[]>([])
    const roleAction = ref<string[] | number>(getCacheRoleAction() || [])
    const homeAction = ref<string>("/")
    routes.value = constantRoutes

    const setRoleAction = (value: string[] | number) => {
        setCacheRoleAction(value)
        roleAction.value = value
    }
    const setHomeAction = (value: string) => {
        homeAction.value = value
    }

    const isRoleActionPath = (path: string) => {
        let actionStatus = false
        if (roleAction.value == -1) {
            actionStatus = true;
        } else if (typeof roleAction.value !== 'number' && roleAction.value.indexOf(path) > -1) {
            actionStatus = true;
        }
        return actionStatus;
    }

    const getRoleActionPath = (path: string) => {
        if (isRoleActionPath(path)) {
            return path;
        }
        return homeAction.value;

    }
    return { routes, roleAction, setRoleAction, isRoleActionPath, getRoleActionPath, setHomeAction }
})
/** 在 setup 外使用 */
export function usePermissionStoreHook() {
    return usePermissionStore(store)
}
