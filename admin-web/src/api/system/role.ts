import { request } from '@/utils/request/default'
export function getListRoleApi(params:any) {
    return request({
        url: 'system.role/list',
        method: 'GET',
        params
    })
}

export function createRoleApi (data:any) {
    return request({
        url: 'system.role/create',
        method: 'POST',
        data
    })
}
export function updateRoleApi (data:any) {
    return request({
        url: `system.role/update`,
        method: 'PUT',
        data
    })
}
export function getDetailRoleApi (params:any) {
    return request({
        url: 'system.role/detail',
        method: 'GET',
        params
    })
}

export function deleteRoleApi (data:{id:number|string}) {
    return request({
        url: `system.role/delete`,
        method: 'DELETE',
        data
    })
}

export function getRoleRule () {
    return request({
        url: `system.role/getRule`,
        method: 'GET'
    })
}

export function saveRules (data:any) {
    return request({
        url: `system.role/saveRules`,
        method: 'PUT',
        data
    })
}
export function getRoleSelectList () {
    return request({
        url: `system.role/getSelectList`,
        method: 'GET'
    })
}