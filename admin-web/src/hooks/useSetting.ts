import { PageLimitType } from "@/types"
import { getCachePageLimit } from "@/utils"
import { ref } from "vue"

/** 当前分页配置 */
const PageLimit = ref<number>(getCachePageLimit() || 20)

const setPageLimit = (value: number) => {
    PageLimit.value = value
}


/** Modal Top */
const ModalTop:string | number | undefined = '50px'

/** 配置 hook */
export function useSetting() {
    return { PageLimit, setPageLimit, ModalTop }
}
