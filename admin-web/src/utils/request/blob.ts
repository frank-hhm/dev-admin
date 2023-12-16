import axios, { AxiosRequestConfig, AxiosResponse } from 'axios'
import { getToken } from "@/utils"
import router from "@/router/index";
import { Result } from "@/types";

export const request = (options: any, isAllow = true) => {
    return new Promise<Result>((resolve, reject) => {
        axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'
        axios.defaults.responseType = 'blob'
        const service = axios.create({
            baseURL: import.meta.env.VITE_BASE_URL, // api 的 base_url
            timeout: 8000000,
        })
        // axios.defaults.withCredentials = true;

        service.interceptors.request.use(
            (config: AxiosRequestConfig) => {
                if (!config.headers) {
                    throw new Error(
                        `Expected 'config' and 'config.headers' not to be undefined`
                    );
                }
                let token: string | unknown = getToken();
                if (token && isAllow) {
                    config.headers['Authori-zation'] = 'Bearer ' + token
                }
                return config
            }, error => {
                Promise.reject(error)
            }
        )
        service.interceptors.response.use(
            (response: AxiosResponse) => {
                return Promise.resolve({
                    data: response.data,
                    code: 'success'
                });
            },
            error => {
                if (error.code === 'ERR_NETWORK') {
                    throw new Error(
                        error.message || '网络异常'
                    );
                } else if (error.data.code === 700) {
                    router.replace({
                        path: "/login",
                        query: {
                            redirect: router.currentRoute.value.fullPath
                        }
                    });
                } else if (!error.response.data?.message && error.response.status != 200) {
                    throw new Error(
                        error.message || error.response.status
                    );
                } else {
                    return Promise.reject(error.response.data)
                }
            }
        )
        // 请求处理
        service(options).then((res: any) => {
            resolve(res)
        }).catch(res => {
            if (res) reject(res)
        })
    })
}


export default request