import { type App } from "vue"
import { initEditorTool } from '@/components/plugins/editor/init'

export function loadEditorPlus(app: App) {
  /** Editor引入 */
  initEditorTool()
}
