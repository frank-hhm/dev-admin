
// 对象的属性是否存在
export const isPropertyExist = (key:string,obj: any) => {
    return obj.hasOwnProperty(key);
}

export default {
    isPropertyExist
}