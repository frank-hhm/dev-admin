import { type App } from "vue"
import ArcoVueIcon from '@arco-design/web-vue/es/icon';

export function loadArcoIconPlus(app: App) {
  app.use(ArcoVueIcon)
}
