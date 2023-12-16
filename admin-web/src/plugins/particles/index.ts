import { type App } from "vue"
import Particles from 'particles.vue3';
export function loadParticlesPlus(app: App) {
  /** Particles 引入 */
  app.use(Particles)
}
