import { request } from '@/utils/request/default'
export const getConfigApi = () => {
    return request({
        url: 'system.config/get',
        method: 'GET'
    })
}
export const saveConfigApi = (data:any) => {
    return request({
        url: 'system.config/save',
        method: 'POSt',
        data
    })
}