// 3.3vite.config.ts具体配置
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import image from 'rollup-plugin-image';
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite';
import { ArcoResolver } from 'unplugin-vue-components/resolvers';
import { vitePluginForArco } from '@arco-plugins/vite-vue'


import path from 'path'

const buildOptions = {
  assetsInclude: ['**/*.jpg', '**/*.png', '**/*.svg'] as string[]
};

export default defineConfig({
  base: './',
  // ...
  plugins: [
    // ...
    AutoImport({
      resolvers: [ArcoResolver()],
    }),
    Components({
      resolvers: [
        ArcoResolver({
          sideEffect: true
        })
      ]
    }),
    vue(),
    vitePluginForArco({
      style: 'css'
    })
  ],
  css: {
    preprocessorOptions: {
      less:{
        modifyVars: {
          'arcoblue-1': '#E8FFEA',
          'arcoblue-2': '#AFF0B5',
          'arcoblue-3': '#7BE188',
          'arcoblue-4': '#4CD263',
          'arcoblue-5': '#23C343',
          'arcoblue-6': '#00B42A',
          'arcoblue-7': '#009A29',
          'arcoblue-8': '#008026',
          'arcoblue-9': '#006622',
          'arcoblue-10': '#004D1C',
        },
        javascriptEnabled: true
      }
    }
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src')
    }
  },
  server: {
    // 是否开启 https
    https: false,
    // 端口号
    port: 80,
    host: 'localhost'
  },
  build: {
    outDir: path.resolve(__dirname, '../server-api/public/admin'),
    rollupOptions: {
      plugins: [
        image({
          extensions: /\.(png|jpg|jpeg)$/
        })
      ]
    }
  },
})
