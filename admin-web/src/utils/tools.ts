import { EnumType, Result, ResultError } from '@/types';
import { Message } from '@arco-design/web-vue';

export const baseApiUrl = () => {
    return import.meta.env.VITE_BASE_URL
}

export const setStorage = (key: string, value: any, time = 0) => {
    localStorage.setItem(key, JSON.stringify({ data: value, time: time == 0 ? -1 : time * 1000 }))
}

export const getStorage = (key: string) => {
    let data = localStorage.getItem(key)
    if (!data) return null
    let dataObj = JSON.parse(data)
    if (dataObj.time != -1 && dataObj.time < new Date().getTime()) {
        localStorage.removeItem(key)
        return null
    } else {
        return dataObj.data
    }
}

export const errorMsg = (res: ResultError) => {
    if (res.code == 'SERVER_ERRPR') {
        Message.error({
            content: res?.data?.message || '服务器请求错误，请稍后再试',
            position: 'top',
            id: 'error',
            duration: 2000
        })
       
    } else if (res.code == 'ERRPR' || res.code == ' ERRPR_AUTH') {
        Message.error({
            content: res?.data?.message || '未知错误',
            position: 'top',
            id: 'error',
            duration: 2000
        })
    } else if (typeof res == 'string') {
        Message.error({
            content: res || '未知错误',
            position: 'top',
            id: 'error',
            duration: 2000
        })
    }
}

export const successMsg = (res: Result | string) => {
    let message = '';
    if (typeof res == 'string') {
        message = res
    } else if (typeof res == 'object') {
        message = res?.message || '处理成功！'
    }
    Message.success({
        content: message,
        position: 'top',
        id: 'success',
        duration: 2000
    })
}

// 复制文本内容
export const copyDomText = (val: string): void => {
    const text = val
    const input = document.createElement('input')
    input.value = text
    document.body.appendChild(input)
    input.select()
    document.execCommand('copy')
    document.body.removeChild(input)
    successMsg('复制成功')
}

export const staticImgPath = (path: string, source = 'assets') => {
    if (!path) return;
    switch (source) {
        case 'assets':
            return new URL('/image/' + path, import.meta.url).href;
            break;
    }
}

//拼接Url参数
export const getUrlParams = (url: string, params: any) => {
    let newUrl = url;
    for (let i in params) {
        newUrl = addOrgToUrl(newUrl, i, params[i]);
    }
    return newUrl;
};
export const addOrgToUrl = (url: string, paramName: string, replaceWith: string) => {
    if (url.indexOf(paramName) > -1) {
        let re = eval('/(' + paramName + '=)([^&]*)/gi');
        url = url.replace(re, paramName + '=' + replaceWith);
    } else {
        let paraStr = paramName + '=' + replaceWith;
        let idx = url.indexOf('?');
        if (idx < 0)
            url += '?';
        else if (idx >= 0 && idx != url.length - 1)
            url += '&';
        url = url + paraStr;
    }
    return url;
};

export const getEnumName = (value: string | number | boolean, enums: EnumType = []) => {
    for (const item of enums) {
        if (value == item.value) {
            return item.name
        }
    }
    return value?.toString() || ''
}

export default {
    baseApiUrl,
    setStorage,
    getStorage,
    errorMsg,
    successMsg,
    copyDomText,
    staticImgPath,
    getUrlParams,
    getEnumName
}