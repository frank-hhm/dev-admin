/** 统一处理 Cookie */
import CacheKey from "@/constants/cache-key"
import Cookies from "js-cookie"
import { formatDatetime } from "@/utils"

export const getToken = () => {
    return Cookies.get(CacheKey.TOKEN)
}
export const setToken = (token: string, exp: number) => {
    Cookies.set(CacheKey.TOKEN, token)
}
export const removeToken = () => {
    Cookies.remove(CacheKey.TOKEN)
}

export default {
    getToken,
    setToken,
    removeToken
}