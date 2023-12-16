import { ref } from "vue"
import store from "@/store"
import { defineStore } from "pinia"
import { getToken, removeToken } from '@/utils'
import { IAdmin } from "@/types"
import { getDefaultDetailApi } from "@/api/system/admin"

export const useAdminStore = defineStore("admin", () => {
    const token = ref<string>(getToken() || "")
    const adminInfo = ref<IAdmin>({})

    const setInfo = (value: IAdmin) => {
        adminInfo.value = value
    }

    const initInfo = async () => {
        const { data }: any = await getDefaultDetailApi()
        adminInfo.value = data
    }

    /** 重置 Token */
    const resetToken = () => {
        removeToken()
        token.value = ""
    }
    return { token, adminInfo,initInfo, setInfo, resetToken }
})

/** 在 setup 外使用 */
export function useAdminStoreHook() {
    return useAdminStore(store)
}


export default {
    useAdminStore,
    useAdminStoreHook
}