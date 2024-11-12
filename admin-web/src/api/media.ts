import { request,requestProgress } from '@/utils/request/default'

export const getMediaGroupListApi = (params:any) => {
    return request({
        url: 'mediaGroup/list',
        method: 'GET',
        params
    })
}
export const createMediaGroupApi = (data:any) => {
    return request({
        url: `mediaGroup/create`,
        method: 'POST',
        data
    })
}
export const updateMediaGroupApi  = (data:any) => {
    return request({
        url: `mediaGroup/update`,
        method: 'PUT',
        data
    })
}

export const deleteMediaGroupApi  = (data:{group_id:number|string}) => {
    return request({
        url: `mediaGroup/delete`,
        method: 'DELETE',
        data
    })
}

export const getMediaListApi  = (params:any) => {
    return request({
        url: 'media/list',
        method: 'GET',
        params
    })
}

export const deleteMediaApi  = (data:{ids:number|string}) => {
    return request({
        url: `media/delete`,
        method: 'DELETE',
        data
    })
}

export const setMediaGroupApi = (data:{ids:number|string,group_id:number|string}) => {
    return request({
        url: `media/setGroup`,
        method: 'PUT',
        data
    })
}

export const updateMediaNameApi = (data:any) => {
    return request({
        url: `media/updateName`,
        method: 'PUT',
        data
    })
}

export const uploadMediaApi = (data:any,options:any = {
    isAllow:true
}) => {
    return requestProgress({
        url: `media/uplaod`,
        method: 'POST',
        data
    },options)
}
export const getCascaderApi = (params:{
    pid:number|string
}) => {
    return request({
        url: `mediaGroup/getCascaderApi`,
        method: 'GET',
        params
    })
}

