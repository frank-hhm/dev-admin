import { type Directive } from "vue"
export const loading: Directive = {
    mounted(el: HTMLElement, binding: any) {
        const isLoading = binding.value;
        if (isLoading) {
            const overlay = document.createElement('div');
            overlay.className = 'loading-spinner';
            el.appendChild(overlay);
            updateClass(el, binding.value);
        }
    },
    updated(el: HTMLElement, binding:any) {
        const isLoading = binding.value;
        const overlay = el.querySelector('.loading-spinner');

        if (isLoading) {
            if (!overlay) {
                const newOverlay = document.createElement('div');
                newOverlay.className = 'loading-spinner';
                el.appendChild(newOverlay);
            }
        } else {
            if (overlay) {
                overlay.remove();    
            }
        }
        updateClass(el, binding.value);
    },
}


function updateClass(el: HTMLElement, isLoading: any) {
    const className = 'loading-container'; 
    if (isLoading) {
      el.classList.add(className);
    } else {
      el.classList.remove(className);
    }
  }