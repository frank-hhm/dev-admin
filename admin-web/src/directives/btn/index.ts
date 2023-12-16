import { type Directive } from "vue"
const btnFun = function (evt: any) {
    let target = evt.target
    if (target.nodeName == 'SPAN') {
        target = evt.target.parentNode
    }
    target.blur()
}
export const btn: Directive = {
    mounted(el) {
        el.addEventListener('focus', btnFun)
    },
    unmounted(el) {
        el.removeEventListener('focus', btnFun)
    }
}