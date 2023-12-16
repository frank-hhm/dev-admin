import { request } from '@/utils/request/default'
export const getCaptchaApi = () => {
    return request({
        url: 'index/captcha',
        method: 'GET'
    }, {
        isAllow: false,
        apiPath: 'index/'
    })
}
export const getSystemInfoApi = () => {
    return request({
        url: 'index/systemInfo',
        method: 'GET'
    }, {
        isAllow: false,
        apiPath: 'index/'
    })
}

export const getEnumApi = () => {
    return request({
        url: 'index/enum',
        method: 'GET'
    }, {
        isAllow: false,
        apiPath: 'index/'
    })
}