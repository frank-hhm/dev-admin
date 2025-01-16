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
export const forgetPasswordApi = (data: {
    email: string;
    code: string;
    pwd: string;
    conf_pwd: string;
}) => {
    return request({
        url: 'login/forgetPassword',
        method: 'POST',
        data
    }, {
        isAllow :false
    })
}