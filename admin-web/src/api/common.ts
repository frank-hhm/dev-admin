import { request, requestProgress } from '@/utils/request/default'
export const getCaptchaApi = () => {
    return request({
        url: 'common.publics/captcha',
        method: 'GET'
    }, {
        isAllow: false,
        apiPath: 'api/'
    })
}
export const getSystemInfoApi = () => {
    return request({
        url: 'common.publics/systemInfo',
        method: 'GET'
    }, {
        isAllow: false,
        apiPath: 'api/'
    })
}

export const getEnumApi = () => {
    return request({
        url: 'common.publics/enum',
        method: 'GET'
    }, {
        isAllow: false,
        apiPath: 'api/'
    })
}

export const getEmailCodeApi = (data:{
    email:string
    captcha_code:string
    captcha_uniqid:string
    type:string
}) => {
    return request({
        url: 'common.publics/getEmailCode',
        method: 'POST',
        data
    }, {
        isAllow: true,
        apiPath: 'api/'
    })
}