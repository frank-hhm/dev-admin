import { App, Plugin } from 'vue';
import copyright from '@/components/plugins/copyright/index.vue'
import uploadBtn from '@/components/plugins/upload-btn/index.vue'
import selectIconModal from '@/components/select-modal/icon/index.vue'
import selectRuleModal from '@/components/select-modal/rule/index.vue'
import shortcutsTime from '@/components/plugins/shortcuts-time/index.vue'
export const ComponentsPlugin: Plugin = {
    install(app: App) {
        app.component('copyright', copyright);
        app.component('upload-btn', uploadBtn);
        app.component('select-icon-modal', selectIconModal);
        app.component('select-rule-modal', selectRuleModal);
        app.component('shortcuts-time', shortcutsTime);
    },
};
export {copyright,uploadBtn,selectIconModal,selectRuleModal,shortcutsTime };