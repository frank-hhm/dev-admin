import axios, { AxiosRequestConfig, AxiosResponse } from 'axios'
import { baseApiUrl, getToken } from "@/utils"
import router from "@/router";
import { Result } from "@/types";
import { useAdminStoreHook } from "@/store";
import { reactive, readonly } from 'vue';

export const request = (params: any, options: any = {
    isAllow: true
}) => {
    return new Promise<Result>((resolve, reject) => {
        axios.defaults.headers.post['Content-Type'] = 'application/json'
        axios.defaults.responseType = 'json'
        const apiPath = options.apiPath || "sys/"
        const service = axios.create({
            baseURL: baseApiUrl() + apiPath, // api 的 base_url
            timeout: 80000
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
                if (token && options.isAllow) {
                    config.headers['Authori-zation'] = 'Bearer ' + token
                }
                config.onUploadProgress = progressEvent => {
                    if (options.progress) {
                        options.progress.percent = progressEvent.loaded / progressEvent.total * 100 - 1 | 0
                    }
                };
                if ( options.signal) {
                    config.signal = options.signal
                }
                return config
            }, error => {
                Promise.reject(error)
            }
        )
        service.interceptors.response.use(
            (response: AxiosResponse) => {
                // console.log("成功，请求结果：",response)
                if (response.data.code === 700 || response.data.code === 701) {
                    useAdminStoreHook().resetToken()
                    useAdminStoreHook().setInfo({})
                    router.replace({
                        path: "/login",
                        query: {
                            redirect: router.currentRoute.value.fullPath
                        }
                    });
                }
                if (response.data.code === 700) {
                    return Promise.reject({
                        data: response.data,
                        code: 'ERRPR_AUTH'
                    });
                } else if (response.data.code !== 1) {
                    return Promise.reject({
                        data: response.data,
                        code: 'ERRPR'
                    });
                }
                return Promise.resolve(response.data)
            },
            error => {
                return Promise.reject({
                    data: error.response.data,
                    code: 'SERVER_ERRPR'
                })
            }
        )
        // 请求处理
        service(params).then((res: any) => {
            resolve(res)
        }).catch(res => {
            if (res) reject(res)
        })
    })
}
export const requestProgress = (params: any, options: any = {
    isAllow: true
}) => {
    const progress = reactive({ percent: 0 });
    return {
        progress: readonly(progress),
        request: request(params, Object.assign({
            progress
        }, options))
    };
};

export default { request, requestProgress }