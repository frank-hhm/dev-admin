import { type App } from "vue"
import { ComponentsPlugin } from '@/components'
import { loadStorePlus } from "./store"
import { loadRouterPlus } from "./router"
import { loadArcoPlus } from "./arco"
import { loadArcoIconPlus } from "./arco-icon"
import { loadParticlesPlus } from "./particles"

export function loadPlugins(app: App) {
    loadStorePlus(app);
    loadRouterPlus(app)
    loadArcoPlus(app)
    loadArcoIconPlus(app)
    loadParticlesPlus(app)
    app.use(ComponentsPlugin)
}
