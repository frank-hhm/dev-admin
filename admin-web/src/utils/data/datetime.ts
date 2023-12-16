/** 
 * 时间戳格式化函数 
 */
export const formatDatetime = (date: any, fmt: string) => {
    let _date = date, _fmt = '';
    if (typeof date == 'number') {
        _date = new Date(date)
    }
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (_date.getFullYear() + '').substr(4 - RegExp.$1.length))
    }
    let o: any = {
        'M+': _date.getMonth() + 1,
        'd+': _date.getDate(),
        'h+': _date.getHours(),
        'm+': _date.getMinutes(),
        's+': _date.getSeconds()
    }
    for (let k in o) {
        if (new RegExp(`(${k})`).test(fmt)) {
            let str = o[k] + ''
            _fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : padLeftZero(str))
        }
    }
    return _fmt
}
function padLeftZero(str: string) {
    return ('00' + str).substr(str.length)
}

export const getDate = (now: any) => {
    var year = now.getFullYear(); //年份
    var month = now.getMonth() + 1; //月份（0-11）
    var date = now.getDate(); //天数（1到31）
    var hour = now.getHours(); //小时数（0到23）
    var minute = now.getMinutes(); //分钟数（0到59）
    var second = now.getSeconds(); //秒数（0到59） 
    return (
        String(year) + String(month) + String(date)
    )
};

export default {
    formatDatetime,
    getDate
}