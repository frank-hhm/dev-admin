import { isNumber } from "../validate";

export const getMenuString = (val: string, isReplace = false) => {
    if (isReplace) {
        let valString = Number(val?.replace(new RegExp("/"), ""));
        return isNaN(valString) ? val : valString;
    }
    var idText: any = getMenuString(val?.substring(val.lastIndexOf("/")), true);
    if (isNaN(idText)) {
        return val
    }
    return val.substring(0, val.lastIndexOf("/"))
}

export const getMenuActionParent = (item: any, val: string) => {
    let is = false
    if (item.action) {
        item.action.forEach((items: any) => {
            if (items.menu_path == val) {
                is = true
            }
        })
    }
    return is;
}

export const getItemPath = (children: any) => {
    let menu: any = {};
    if (!children || children.length == 0) {
        return children;
    }
    for (let index = 0; index < children.length; index++) {
        menu = children[index];
        if (menu.children && menu.children.length > 0) {
            menu = getItemPath(menu.children);
        }
        if (menu.menu_path) {
            return menu;
        }
    }
    return children;
}

export const toNumber = (str: string) => {
    return Number(str)
}

// 配音时长
export const getStringDuration = (str: string) => {
    let time = palindrome(str).length / 4.5;
    return time.toFixed(2);
}

export const palindrome = (str: string) => {
    var _str = str.replace(/[`:_.~!@#$%^&*() \+ =<>?"{}|, \/ ;' \\ [ \] ·~！@#￥%……&*（）—— \+ ={}|《》？：“”【】、；‘’，。、]/g,
        '');
    return _str.trim();
}
export const getFileSize = (size: number) => {
    if (!size)
        return size;

    var num = 1024.00; //byte

    if (size < num)
        return size + "B";
    if (size < Math.pow(num, 2))
        return (size / num).toFixed(2) + "K"; //kb
    if (size < Math.pow(num, 3))
        return (size / Math.pow(num, 2)).toFixed(2) + "M"; //M
    if (size < Math.pow(num, 4))
        return (size / Math.pow(num, 3)).toFixed(2) + "G"; //G
    return (size / Math.pow(num, 4)).toFixed(2) + "T"; //T
}

export const getFileSizeUnit = (size: number) => {
    if (!size)
        return {
            size:size,
            unit:''
        };

    var num = 1024.00; //byte

    if (size < num)
        return {
            size:size, //kb
            unit:"B"
        }
    if (size < Math.pow(num, 2))
        return {
            size: (size / num).toFixed(2), //kb
            unit:"K"
        }
    if (size < Math.pow(num, 3))
        return {
            size:(size / Math.pow(num, 2)).toFixed(2), //M
            unit:"M"
        }
    if (size < Math.pow(num, 4))
        return {
            size:(size / Math.pow(num, 3)).toFixed(2), //G
            unit:"G"
        }
    return {
        size:(size / Math.pow(num, 4)).toFixed(2),
        unit:"T"
    }; //T
}
export const toMoneyFormater = (data: number | string) => {
    if(!isNumber(data)){
        return data;
    }
    let _string = Number(data);
    if (!data) return '0.00'
    // 将数据分割，保留两位小数
    data = _string.toFixed(2)
    // 获取整数部分
    const intPart = Math.trunc(_string)
    // 整数部分处理，增加,
    const intPartFormat = intPart.toString().replace(/(\d)(?=(?:\d{3})+$)/g, '$1,')
    // 预定义小数部分
    let floatPart = '.00'
    // 将数据分割为小数部分和整数部分
    const newArr = data.toString().split('.')
    if (newArr.length === 2) { // 有小数部分
      floatPart = newArr[1].toString() // 取得小数部分
      return intPartFormat + '.' + floatPart
    }
    return intPartFormat + floatPart
}


export default {
    getMenuString,
    getMenuActionParent,
    getItemPath,
    toNumber,
    getStringDuration,
    palindrome,
    getFileSize,
    toMoneyFormater,
    getFileSizeUnit
}