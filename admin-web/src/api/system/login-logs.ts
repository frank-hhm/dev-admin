import { request } from '@/utils/request/default'


export function getListSystemLoginLogsApi(params: any) {
    return request({
        url: 'system.loginLogs/list',
        method: 'GET',
        params
    })
}

export function getListSystemLoginLogsByIdApi(id:number|string,params: any,source:string = 'admin') {
    return request({
        url: 'system.loginLogs/list',
        method: 'GET',
        params:{
            ...params,
            id:id,
            source:source
        }
    })
}
