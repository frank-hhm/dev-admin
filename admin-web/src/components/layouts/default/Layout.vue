<template>
  <LayoutNav></LayoutNav>
  <div class="layout">
    <div class="layout-side" :class="{ 'hidden-menu': hiddenMenu }">
      <div class="layout-side-left">
        <LayoutSideMenu ref="LayoutSideMenuRef" @change="selectChange"></LayoutSideMenu>
      </div>
      <div class="layout-side-childen" v-if="childrenMenu.length">
        <a-menu v-if="!menusLoad" :auto-open-selected="true" :default-selected-keys="[activeMenu]" :default-open-keys="parentPath" >
          <LayoutSideMenuChilden ref="LayoutSideMenuChildenRef" :data="childrenMenu"></LayoutSideMenuChilden>
        </a-menu >
      </div>
    </div>

    <div class="layout-body" :class="{ 'hidden-menu': hiddenMenu }" v-if="!menusLoad">
      <router-view></router-view>
    </div>
    <div class="layout-footer" :class="{ 'hidden-menu': hiddenMenu }">
      <copyright></copyright>
    </div>
  </div>
</template>
<script lang="ts" setup>
import LayoutNav from "@/components/layouts/LayoutNav.vue";
import LayoutSideMenu from "./LayoutSideMenu.vue";
import LayoutSideMenuChilden from "./LayoutSideMenuChilden.vue";
import common from "@/components/layouts/common";
import {
  ref,
  getCurrentInstance,
  onMounted,
  nextTick,
  computed,
} from "vue";
import { useRoute } from "vue-router";

const {
  proxy,
  proxy: { $utils },
} = getCurrentInstance() as any;

const route = useRoute();

const menusLoad = ref<boolean>(true);

const hiddenMenu = ref<boolean>(true);

const LayoutSideMenuRef = ref<HTMLElement>;

const childrenMenu = ref<any>([]);

const LayoutSideMenuChildenRef = ref<HTMLElement>();

const selectChange = (children: any) => {
  childrenMenu.value = children;
  _common.getParent(childrenMenu.value)
  hiddenMenu.value = childrenMenu.value.length == 0 ? true : false;
  nextTick(() => {
    proxy?.$refs["LayoutSideMenuChildenRef"]?.refreshMenuList(
      childrenMenu.value
    );
  });
};

onMounted(() => {
  
});

const activeMenu = computed(() => {
  const { meta, path } = route;
  if (meta.activeMenu) {
    return meta.activeMenu as string;
  }
  return path;
});

const parentPath = ref<string[]>([]);

const _common = common((res: any) => {
  parentPath.value = res
  nextTick(() => {
    menusLoad.value = false;
  });
});

</script>
<style scoped>
.layout {}

.layout-side {
  width: 220px;
  position: fixed;
  z-index: -1;
  background: var(--color-bg-1);
  height: 100%;
}

.layout-side.hidden-menu {
  width: 90px;
}

.layout-side .el-menu {
  border-right: none;
}

.layout-side-left {
  height: 100%;
  overflow-x: hidden;
  position: fixed;
  width: 60px;
  z-index: 99;
  height: calc(100% - 60px);
  top: 51px; 
  padding: 10px 10px;
  border-right: 1px solid var(--color-border-1);
}

.layout-side-childen {
  width: 140px;
  height: 100%;
  overflow-x: hidden;
  position: fixed;
  z-index: 99;
  height: calc(100% - 50px);
  left: 81px;
  top: 51px;
  /* padding: 10px; */
  border-right: 1px solid var(--color-border-1);
  background: var(--color-bg-1);
}

.layout-body {
  left: 222px;
  top: 51px;
  position: fixed;
  width: calc(100% - 222px - (2*var(--base-padding)));
  height: calc(100% - (2*var(--base-padding)) - 51px - 51px);
  padding: var(--base-padding);
  background: var(--color-bg-body);
  overflow-y: scroll;
}

.layout-body.hidden-menu {
  left: 81px;
  width: calc(100% - 101px);
}

.layout-footer {
  width: calc(100% - 222px);
  left: 222px;
  text-align: center;
  position: fixed;
  bottom: 0;
  height: 50px;
  background: var(--color-bg-1);
  border-top: 1px solid var(--color-border-1);
  line-height: 40px;
  z-index: -1;
}

.layout-footer.hidden-menu {
  left: 81px;
  width: 100%;
}

.layout-footer img {
  margin: 10px auto;
  height: 30px;
}

html.dark .layout-body {
  background: var(--color-bg-1);
}
</style>