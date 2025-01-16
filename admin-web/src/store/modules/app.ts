import { reactive, ref } from "vue"
import store from "@/store"
import { getSystemInfoApi } from "@/api/common";
import { Result, SystemInfoType } from "@/types"
import { defineStore } from "pinia"
import { setCacheSystemInfo, getCacheSystemInfo, getCacheTemplateDark,setCacheTemplateDark, getCacheLayout, setCacheLayout } from "@/utils";
export const useAppStore = defineStore("app", () => {

    const isMapScriptLoad = ref<boolean>(false)
    const isMobile = ref<boolean>(false)

    const layout = ref<string>(getCacheLayout() || "default")

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
        setCacheTemplateDark(isDark.value)
    }
    const setMapScriptLoad = () => {
        isMapScriptLoad.value = true
    }
    const setLayout = (_layout: string) => {
        layout.value = _layout
        setCacheLayout(layout.value)
    }

    const setMobile = (value: boolean) => {
        isMobile.value = value
        if(isMobile.value){
            setLayout('layout1')
        }
    }

    return { isDark, setDark, systemInfo, getSystemInfo, isMapScriptLoad, setMapScriptLoad, layout, setLayout,setMobile,isMobile }
})

/** 在 setup 外使用 */
export function useAppStoreHook() {
    return useAppStore(store)
}

export default {
    useAppStore,
    useAppStoreHook
}