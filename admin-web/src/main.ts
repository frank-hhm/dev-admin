import { createApp } from 'vue'
import App from "@/App.vue"
import router from '@/router'
import "@/router/permission"

// load
import { loadPlugins } from "@/plugins"
import { loadDirectives } from "@/directives"
//css
import "@/styles/index.scss"

import * as Utils from '@/utils'

const app = createApp(App);

/** 加载插件 */
loadPlugins(app)
/** 加载自定义指令 */
loadDirectives(app)

app.config.globalProperties.$utils = Utils;

router.isReady().then(() => {
    app.mount("#app")
}).catch(err => {
    console.log(err)
})

