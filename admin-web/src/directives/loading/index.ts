import { nextTick, type Directive } from "vue"

export const loading: Directive = {
    mounted(el: HTMLElement, binding: any) {
        
    if (binding.value || typeof binding.value === "undefined")
    el.classList.add("state-loading");
  else el.classList.remove("state-loading");
        nextTick(() => {
            updateClass(el, binding.value);
        });
    },
    updated(el: HTMLElement, binding: any) {

        if (binding.value) el.classList.add("state-loading");
        else el.classList.remove("state-loading");
        nextTick(() => {
            updateClass(el, binding.value);
        });
    }
}

function updateClass(el: HTMLElement, isLoading: any) {
    const className = 'loading-container';
    if (isLoading) {
        el.classList.add(className);
    } else {
        el.classList.remove(className);
    }
}