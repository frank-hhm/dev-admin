
export const includeArray = (list1: any, list2: any) => {
    let status = false
    if (list1 === true) {
      return true
    } else {
      if (typeof list2 !== 'object') {
        return false
      }
      list2.forEach((item: any) => {
        if (list1.includes(item)) status = true
      })
      return status
    }
  }
  
  // 是否存在数组里
  export const inArray = (search: any, array: any) => {
    for (var i in array) {
      if (array[i] == search) {
        return true;
      }
    }
    return false;
  }
  
  // 对象转数组
  export const toArray = (obj: any) => {
    var arr:any = [];
    for (let i in obj) {
      if (obj[i] != null && obj[i] != undefined) arr.push(obj[i])
    };
    return arr;
  }
  export const arrayFilterMultiple = (array: any, key: number | string, value: string | number) => {
    for (var index in array) {
      if (array[index] == undefined) {
        return -1;
      }
      if (key && array[index][key] == value) return Number(index);
      if (!key && array[index] == value) return Number(index);
    }
    return -1;
  }
  
  // 根据二维数组指定对象的元素值获取对象
  export const getArrayItemByKeyValue = (array: any, key: number | string, value: string | number) => {
    for (var index in array) {
      if (array[index] == undefined) {
        return {};
      }
      if (key && array[index][key] == value) return array[index];
      if (!key && array[index] == value) return array[index];
    }
    return {};
  }
  
  export const getArrayItemValueByKeyValue = (array: any, key: number | string, value: string | number, keys: string | number) => {
    for (var index in array) {
      if (keys && array[index] == undefined) {
        return '';
      }
      if (array[index] == undefined) {
        return {};
      }
      if (key && array[index][key] == value) return array[index][keys] || array[index];
      if (!key && array[index] == value) return array[index][keys] || array[index];
    }
    if (keys) {
      return '';
    }
    return {};
  }
  
  export const getArrayColumnByKey = (array: any, key: number | string) => {
    let newData: any = [];
    for (var index in array) {
      if (array[index] == undefined) {
        return {};
      }
      if (key && array[index][key]) newData.push(array[index][key]);
    }
    return newData;
  }
  
  
  export const getArrayColumn2ByKey = (array: any, key: number | string) => {
    let newData: any = [];
    for (var index in array) {
      if (array[index] == undefined) {
        return {};
      }
      if (key && array[index][key]) {
        array[index][key].forEach((item: any) => {
          newData.push(item)
        })
  
      };
    }
    return newData;
  }
  
  // 根据二维数组指定对象的元素值获取对象集
  export const getArrayColumnsByKeyValue = (array: any, key: number | string, value: string | number) => {
    let newData: any = [];
    for (var index in array) {
      if (array[index] == undefined) {
        return {};
      }
      if (key && array[index][key] == value) newData.push(array[index]);
      if (!key && array[index] == value) newData.push(array[index]);
    }
    return newData;
  }
  
  export const arraySplice = (array: any, value: any) => {
    for (var index in array) {
      if (array[index] == value) {
        array.splice(index, 1)
      }
    }
    return array;
  }
  
  export const getArrayDataByKey = (arr: object[], key: string) => {
    if (arr.length == 0 || arr == null) return [];
    let data: any = [];
    arr.forEach((item: any) => {
      if (item[key]) {
        data.push(item[key])
      }
    });
    return data;
  }
  
  // 获取最小
  export const getArrayMin = (_array: number[]) => {
    var minNum = _array[0];
    _array.forEach((val: number, index: number) => {
      if (val < _array[index + 1] && val < minNum) {
        minNum = val;
      }
    })
    return minNum;
  }
  
  //数组合并
  export const arrayMerge = (arr1: [], arr2: []) => {
    return arr1.concat(arr2);
  }
  
  export default {
    includeArray,
    inArray,
    toArray,
    arrayFilterMultiple,
    getArrayItemByKeyValue,
    getArrayColumnsByKeyValue,
    getArrayColumnByKey,
    getArrayColumn2ByKey,
    arraySplice,
    getArrayDataByKey,
    arrayMerge,
    getArrayMin
  }