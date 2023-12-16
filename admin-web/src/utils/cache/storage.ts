/** 统一处理 localStorage */

import CacheKey from "@/constants/cache-key"
import { SystemInfoType, MenusListsType, EnumListsType, PageLimitType } from "@/types"
import { getStorage, setStorage } from "@/utils"

export const getCacheSystemInfo = () => {
  return getStorage(CacheKey.SYSTEM_INFO)
}
export const setCacheSystemInfo = (data: SystemInfoType) => {
  setStorage(CacheKey.SYSTEM_INFO, data)
}
export const getCacheTemplateDark = () => {
  return getStorage(CacheKey.TEMPLATE_DARK)
}
export const setCacheTemplateDark = (data: boolean) => {
  setStorage(CacheKey.TEMPLATE_DARK, data)
}

export const getCacheMenus = () => {
  return getStorage(CacheKey.MENUS)
}
export const setCacheMenus = (data: MenusListsType) => {
  setStorage(CacheKey.MENUS, data)
}


export const getCacheEnum = () => {
  return getStorage(CacheKey.ENUM)
}
export const setCacheEnum = (data: EnumListsType) => {
  setStorage(CacheKey.ENUM, data)
}


export const getCachePageLimit = () => {
  return getStorage(CacheKey.PAGE_LIMIT)
}
export const setCachePageLimit = (data: number) => {
  setStorage(CacheKey.PAGE_LIMIT, data)
}


export const getCacheRoleAction = () => {
  return getStorage(CacheKey.ROLE_ACTION)
}
export const setCacheRoleAction = (data: string[] | number) => {
  setStorage(CacheKey.ROLE_ACTION, data)
}

export default {
  getCacheSystemInfo,
  setCacheSystemInfo,
  getCacheTemplateDark,
  setCacheTemplateDark,
  getCacheMenus,
  setCacheMenus
}