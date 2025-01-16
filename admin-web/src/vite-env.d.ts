/// <reference types="vite/client" />
/// <reference types="vite-plugin-pages/client" />

declare module 'particles.vue3';

declare module 'vue-cropper';

declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}

declare interface Window {
  TMap?:any;
  _TMapSecurityConfig?:any;
}