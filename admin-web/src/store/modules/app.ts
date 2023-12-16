import { reactive, ref } from "vue"
import store from "@/store"
import { getSystemInfoApi } from "@/api/common";
import { Result, SystemInfoType } from "@/types"
import { defineStore } from "pinia"
import { setCacheSystemInfo, getCacheSystemInfo, getCacheTemplateDark } from "@/utils";
export const useAppStore = defineStore("app", () => {

    const isDark = ref<boolean>(getCacheTemplateDark() || false)

    const systemInfo = ref<SystemInfoType>(getCacheSystemInfo() || {
        system_name: import.meta.env.VITE_BASE_SYSTEM_NAME,
        system_version: '1',
    })

    /** 获取系统信息 */
    const getSystemInfo = async () => {
        const { data }: Result = await getSystemInfoApi()
        setCacheSystemInfo(data);
        systemInfo.value = data
    }

    const setDark = (val: boolean) => {
        isDark.value = val
        if (isDark.value) {
            document.body.setAttribute('arco-theme', 'dark')
        } else {
            document.body.removeAttribute('arco-theme');
        }
    }
    return { isDark, setDark, systemInfo, getSystemInfo }
})

/** 在 setup 外使用 */
export function useAppStoreHook() {
    return useAppStore(store)
}

export default {
    useAppStore,
    useAppStoreHook
}