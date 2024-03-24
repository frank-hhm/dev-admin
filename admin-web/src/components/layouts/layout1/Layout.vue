<template>
  <LayoutNav></LayoutNav>
  <div class="layout">
    <div class="layout-side">
      <a-menu v-if="!menusLoad" :style="[
        `width:200px`,
        `height:100%`
      ]" :auto-open-selected="true" :default-selected-keys="[activeMenu]" :default-open-keys="parentPath">
        <LayoutSideMenu ref="LayoutSideMenuRef" :data="menus"></LayoutSideMenu>
      </a-menu>
    </div>

    <div class="layout-body" v-if="!menusLoad">
      <router-view></router-view>
    </div>
    <div class="layout-footer">
      <copyright></copyright>
    </div>
  </div>
</template>
<script lang="ts" setup>
import {
  ref,
  getCurrentInstance,
  onMounted,
  nextTick,
  watch,
  computed,
} from "vue";
import LayoutNav from "@/components/layouts/LayoutNav.vue";
import LayoutSideMenu from "./LayoutSideMenu.vue";
import { useMenusStore } from "@/store";
import { storeToRefs } from "pinia";
import router from "@/router";
import { useRoute } from "vue-router";
import common from "@/components/layouts/common";

const { menus } = storeToRefs(useMenusStore());

const route = useRoute();

const {
  proxy,
  proxy: { $utils },
} = getCurrentInstance() as any;

const menusLoad = ref<boolean>(true);

const activeMenu = computed(() => {
  const { meta, path } = route;
  if (meta.activeMenu) {
    return meta.activeMenu as string;
  }
  return path;
});

watch(
  () => menus.value,
  (val) => {
    menusLoad.value = true;
    menus.value = val;
    nextTick(() => {
      menusLoad.value = false;
    });
  },
  { deep: true }
);

const parentPath = ref<string[]>([]);

const _common = common((res: any) => {
  parentPath.value = res
  nextTick(() => {
    menusLoad.value = false;
  });
});

onMounted(() => {
  _common.getParent(menus.value)
});

</script>
<style scoped>
.layout {}

.layout-side {
  width: 200px;
  position: fixed;
  z-index: -1;
  background: var(--color-bg-1);
  height: calc(100% - 51px);
  -webkit-transition: all 0.1s ease-in-out;
  transition: all 0.1s ease-in-out;
  border-right: 1px solid var(--color-border-1);
}

.layout-side.hidden-menu {
  width: 90px;
}

.layout-side-childen {
  width: 120px;
  height: 100%;
  overflow-x: hidden;
  position: fixed;
  z-index: 99;
  height: calc(100% - 50px);
  left: 90px;
  top: 51px;
  padding: 10px;
  -webkit-transition: all 0.1s ease-in-out;
  transition: all 0.1s ease-in-out;
  border-right: 1px solid var(--el-border-color-light);
}

.layout-body {
  left: 201px;
  top: 51px;
  position: fixed;
  width: calc(100% - 201px - (2*var(--base-padding)));
  height: calc(100% - (2*var(--base-padding)) - 51px - 51px);
  padding: var(--base-padding);
  background: var(--color-border-1);
  overflow-y: scroll;
}

.layout-footer {
  width: calc(100% - 201px);
  left: 201px;
  text-align: center;
  position: fixed;
  bottom: 0;
  height: 50px;
  background: var(--color-bg-1);
  border-top: 1px solid var(--color-border-1);
  line-height: 40px;
  z-index: -1;
}


.layout-footer img {
  margin: 10px auto;
  height: 30px;
}

html.dark .layout-body {
  background: var(--color-bg-1);
}
</style>