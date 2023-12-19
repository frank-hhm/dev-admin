export const getScript = (src: string, callback: Function | unknown) => {
    var protocol = (window.location.protocol == "https:") ? "https://" : "http://";
    src = src && (src.indexOf("http://") === 0 || src.indexOf("https://") === 0) ? src : protocol + src;
    var pageScript = document.createElement('script'),
        head = document.getElementsByTagName('head')[0];
    pageScript.type = 'text/javascript';
    pageScript.src = src;
    pageScript.onload = pageScript.onratechange = () => {
        if (callback && typeof callback == 'function') {
            console.log("js加载完成")
            callback()
        }
    }
    head.appendChild(pageScript);
}
export default {
    getScript
}