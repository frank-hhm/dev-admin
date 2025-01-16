


export const required = (checkVal: string | number) => {
    var reg = new RegExp('/[\S]+/');
    return reg.test(String(checkVal));
}

export const isNumber = (checkVal: string | number) => {
    var reg = /^-?[1-9][0-9]?.?[0-9]*$/;
    return reg.test(String(checkVal));
}

export const isPhone = (checkVal: string | number) => {
    var reg = /^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/;
    return reg.test(String(checkVal));
}
export const isEmail = (checkVal: string | number) => {
    var reg = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return reg.test(String(checkVal));
}

export default {
    required,
    isNumber,
    isPhone,
    isEmail
}