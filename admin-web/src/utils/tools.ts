import { EnumType, Result, ResultError } from '@/types';
import { Message } from '@arco-design/web-vue';
import { request } from './request/default';
import { useAppStoreHook } from '@/store';

export const baseApiUrl = () => {
    let domain = window.location.protocol+"//"+window.location.hostname + '/'
    if (process.env.NODE_ENV === 'development') {
        domain = import.meta.env.VITE_BASE_URL
    }
    return domain
}
export const baseWsUrl = () => {
    let domain = "ws:://"+window.location.hostname + '/'
    if (process.env.NODE_ENV === 'development') {
        domain = import.meta.env.VITE_BASE_WS_URL
    }
    return domain
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
    if (res.code == 'SERVER_ERRPR' || res.code === 0) {
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
            return new URL('../assets/image/' + path, import.meta.url).href;
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

export const downloadVideo = (url: string, fileName: string = '') => {
    const a = document.createElement('a')
    a.href = url
    if (fileName) {
        a.download = fileName
    }
    a.target = '_blank';
    a.style.display = 'none'
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
};
export const  isMobileOrSmallScreen = (width: number = 699) => {
    // 检查屏幕宽度是否小于699px
    return window.innerWidth < width;
}

export const setDocumentTitle = (menu_name: string) => {
    if (menu_name) {
        const useAppStore = useAppStoreHook();
        let baseTitle = useAppStore?.systemInfo?.system_name || ""
        if (!baseTitle) {
            baseTitle = import.meta.env.VITE_BASE_SYSTEM_NAME;
        }
        document.title = baseTitle + '-' + menu_name
    }
}

export default {
    baseApiUrl,
    baseWsUrl,
    setStorage,
    getStorage,
    errorMsg,
    successMsg,
    copyDomText,
    staticImgPath,
    getUrlParams,
    getEnumName,
    downloadVideo,
    isMobileOrSmallScreen,
    setDocumentTitle
}