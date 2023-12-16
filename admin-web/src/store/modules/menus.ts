import { ref } from "vue"
import store from "@/store"
import { defineStore } from "pinia"
import { getCacheMenus, setCacheMenus } from "@/utils"
import { MenusListsType } from "@/types"
export const useMenusStore = defineStore("menus", () => {
    const menus = ref<MenusListsType>(getCacheMenus() || [])

    const setMenus = (value: MenusListsType) => {
        setCacheMenus(value)
        menus.value = value
    }
    return { menus, setMenus }
})

/** 在 setup 外使用 */
export function useMenusStoreHook() {
    return useMenusStore(store)
}


export default {
    useMenusStore,
    useMenusStoreHook
}                               