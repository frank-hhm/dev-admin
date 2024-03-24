import { ref } from "vue"
import store from "@/store"
import { defineStore } from "pinia"
import { getCacheEnum, setCacheEnum } from "@/utils"
import { EnumListsType, Result } from "@/types"
import { getEnumApi } from "@/api/common"
export const useEnumStore = defineStore("Enum", () => {

    const enums = ref<EnumListsType>(getCacheEnum() || [])

    const setEnum = (value: EnumListsType) => {
        setCacheEnum(value)
        enums.value = value
    }

    const getEnumItem = (itemKey: string) => {
        try {
            return enums.value[itemKey]
        } catch (e) {
            return []
        }
    }

    /** 获取 */
    const initEnum = async () => {
        const { data }: Result = await getEnumApi()
        setCacheEnum(data);
        enums.value = data
    }
    return { enums, setEnum, getEnumItem,initEnum }
})

/** 在 setup 外使用 */
export function useEnumStoreHook() {
    return useEnumStore(store)
}


export default {
    useEnumStore,
    useEnumStoreHook
}