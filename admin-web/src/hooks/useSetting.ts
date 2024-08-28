import { PageLimitType } from "@/types"
import { getCachePageLimit, setCachePageLimit } from "@/utils"
import { ref } from "vue"

/** 当前分页配置 */
const PageLimit = ref<number>(getCachePageLimit() || 10)

const setPageLimit = (value: number) => {
    PageLimit.value = value
    setCachePageLimit(value)
}


/** Modal Top */
const ModalTop: string | number | undefined = '50px'

/** 配置 hook */
export function useSetting() {
    return { PageLimit, setPageLimit, ModalTop }
}
