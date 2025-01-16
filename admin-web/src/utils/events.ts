export const eventsListener = (event: string, callback: (event: CustomEvent) => void,isThrottle:boolean = false,throttleDelay: number = 2000) => {
    if(isThrottle){
        const throttleCallback = throttle(throttleDelay,callback)
        window.addEventListener(event, (e: any) => {
            throttleCallback();
        });
        return
    }
    window.addEventListener(event, (e: any) => {
        callback(e);
    });
};

export const dispatchEvents = (event: string, detail?: any) => {
    const customEvent = new CustomEvent(event, { detail });
    window.dispatchEvent(customEvent);
};

const throttle = (delay: number = 2000,callback: Function) => {
    var startTime = new Date().getTime();
    var timer: string | number | undefined | any  = undefined;
    return () => {
        var currentTime = new Date().getTime();
        if(timer){
            clearTimeout(timer)
        }
        if(currentTime - startTime>delay){
            // console.log("大于一秒...");
            callback();
            startTime = currentTime; // 注意：是startTime=curerntTime!
            // 不是currentTime=startTime
        }else{
            // console.log("小于一秒...");
            timer = setTimeout(() => {
                callback();
            },delay)
        }
    }
}

export default {
    eventsListener,
    dispatchEvents
}