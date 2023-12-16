import { type App } from "vue"
import router from '@/router';
export function loadRouterPlus(app: App) {
  /** router 引入 */
  app.use(router)
}
