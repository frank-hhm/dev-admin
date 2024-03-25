import { type App } from "vue"
import ArcoVue from '@arco-design/web-vue';
// import '@arco-design/web-vue/dist/arco.less';
import '@arco-themes/vue-frank-green/index.less';

export function loadArcoPlus(app: App) {
  app.use(ArcoVue)
}
