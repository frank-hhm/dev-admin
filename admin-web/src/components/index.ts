import { App, Plugin } from 'vue';
import LayoutBody from '@/components/layouts/LayoutBody.vue'
import LayoutBodyTabs from '@/components/layouts/LayoutBodyTabs.vue'
import LayoutBodyContent from '@/components/layouts/LayoutBodyContent.vue'
import copyright from '@/components/plugins/copyright/index.vue'
import uploadBtn from '@/components/plugins/upload-btn/index.vue'
import selectIconModal from '@/components/select-modal/icon/index.vue'
import selectRuleModal from '@/components/select-modal/rule/index.vue'
import shortcutsTime from '@/components/plugins/shortcuts-time/index.vue'
import editor from '@/components/plugins/editor/index.vue'
import videoPlay from '@/components/plugins/video-play/index.vue'
import searchMap from '@/components/plugins/search-map/index.vue'
import page from '@/components/plugins/page/index.vue'
import audioPlay from '@/components/plugins/audio-play/index.vue'
import textOverflow from '@/components/plugins/text-overflow/index.vue'

export const ComponentsPlugin: Plugin = {
    install(app: App) {
        app.component('layout-body', LayoutBody);
        app.component('layout-body-tabs', LayoutBodyTabs);
        app.component('layout-body-content', LayoutBodyContent);
        app.component('copyright', copyright);
        app.component('upload-btn', uploadBtn);
        app.component('select-icon-modal', selectIconModal);
        app.component('select-rule-modal', selectRuleModal);
        app.component('shortcuts-time', shortcutsTime);
        app.component('editor', editor);
        app.component('video-play', videoPlay);
        app.component('search-map', searchMap);
        app.component('page', page);
        app.component('audio-play', audioPlay);
        app.component('text-overflow', textOverflow);

    },
};
export {  LayoutBody, LayoutBodyTabs, LayoutBodyContent,copyright, uploadBtn, selectIconModal, selectRuleModal, shortcutsTime, editor, videoPlay, searchMap, page, audioPlay,textOverflow };