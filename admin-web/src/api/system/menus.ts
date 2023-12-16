import { request } from '@/utils/request/default'

export function getCascaderMenusApi(params:any) {
    return request({
        url: 'system.menus/getCascader',
        method: 'GET',
        params
    })
}
export function getRuleListMenusApi() {
    return request({
        url: 'system.menus/ruleList',
        method: 'GET'
    })
}



export function getDetailMenusApi (params:{id:number|string}) {
    return request({
        url: `system.menus/detail`,
        method: 'GET',
        params
    })
}

export function getListMenusApi (params:any) {
    return request({
        url: 'system.menus/list',
        method: 'GET',
        params
    })
}

export function createMenusApi (data:any) {
    return request({
        url: 'system.menus/create',
        method: 'POST',
        data
    })
}

export function updateMenusApi (data:any) {
    return request({
        url: `system.menus/update`,
        method: 'PUT',
        data
    })
}
export function updateStatusMenusApi (data:any) {
    return request({
        url: `system.menus/status`,
        method: 'PUT',
        data
    })
}

export function deleteMenusApi (params:{id:number|string}) {
    return request({
        url: `system.menus/delete`,
        method: 'DELETE',
        params
    })
}