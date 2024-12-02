import { type App } from "vue"
import { permissionDisabled } from "./permission-disabled"
import { permission } from "./permission"
import { loading } from "./loading"
import { btn } from "./btn"

/** 挂载自定义指令 */
export function loadDirectives(app: App) {
  // 权限
  app.directive("permission-disabled", permissionDisabled)
  app.directive("permission", permission)
  app.directive("loading", loading)
  app.directive("btn", btn)

}