import { type App } from "vue"
import store from '@/store';
export function loadStorePlus(app: App) {
  /** store 引入 */
  app.use(store)
}
