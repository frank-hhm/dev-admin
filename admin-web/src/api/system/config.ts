import { request } from '@/utils/request/default'
export const getConfigApi = (type:string) => {
    return request({
        url: 'system.config/get',
        method: 'GET',
        params: { type }
    })
}
export const saveConfigApi = (data:any) => {
    return request({
        url: 'system.config/save',
        method: 'POSt',
        data
    })
}