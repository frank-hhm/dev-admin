import { request } from '@/utils/request/default'
export const loginApi = (data: any) => {
    return request({
        url: 'login/index',
        method: 'POST',
        params: data
    }, {
        isAllow :false
    })
}

export const logoutApi = () => {
    return request({
        url: 'system.admin/logout',
        method: 'GET'
    })
}