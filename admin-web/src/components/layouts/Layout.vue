<template>
  <LayoutNav></LayoutNav>
  <div class="layout">
    <div class="layout-side" :class="{ 'hidden-menu': hiddenMenu }">
      <div class="layout-side-left">
        <LayoutSideMenu ref="LayoutSideMenuRef" @change="selectChange"></LayoutSideMenu>
      </div>
      <div class="layout-side-childen" v-if="childrenMenu.length">
        <LayoutSideMenuChilden ref="LayoutSideMenuChildenRef" :data="childrenMenu"></LayoutSideMenuChilden>
      </div>
    </div>

    <div class="layout-body" :class="{ 'hidden-menu': hiddenMenu }" v-if="!bodyLoad">
      <router-view></router-view>
    </div>
    <div class="layout-footer" :class="{ 'hidden-menu': hiddenMenu }">
      <copyright></copyright>
    </div>
  </div>
</template>
<script lang="ts" setup>
import LayoutNav from "./LayoutNav.vue";
import LayoutSideMenu from "./LayoutSideMenu.vue";
import LayoutSideMenuChilden from "./LayoutSideMenuChilden.vue";
import {
  ref,
  getCurrentInstance,
  onMounted,
  nextTick,
} from "vue";

const {
  proxy,
  proxy: { $utils },
} = getCurrentInstance() as any;

const bodyLoad = ref<boolean>(true);

const hiddenMenu = ref<boolean>(true);

const LayoutSideMenuRef = ref<HTMLElement>;

const childrenMenu = ref<any>([]);

const LayoutSideMenuChildenRef = ref<HTMLElement>();

const selectChange = (children: any) => {
  childrenMenu.value = children;
  hiddenMenu.value = childrenMenu.value.length == 0 ? true : false;
  nextTick(() => {
    proxy?.$refs["LayoutSideMenuChildenRef"]?.refreshMenuList(
      childrenMenu.value
    );
  });
};

onMounted(() => {
  nextTick(() => {
    bodyLoad.value = false;
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
  width: 80px;
}

.layout-side .el-menu {
  border-right: none;
}

.layout-side-left {
  height: 100%;
  overflow-x: hidden;
  position: fixed;
  width: 50px;
  z-index: 99;
  height: calc(100% - 60px);
  top: 51px;
  padding: 10px 15px;
  border-right: 1px solid var(--color-border-1);
}

.layout-side-childen {
  width: 120px;
  height: 100%;
  overflow-x: hidden;
  position: fixed;
  z-index: 99;
  height: calc(100% - 60px);
  left: 80px;
  top: 51px;
  padding: 10px;
  border-right: 1px solid var(--color-border-1);
}

.layout-body {
  left: 221px;
  top: 51px;
  position: fixed;
  width: calc(100% - 261px);
  height: calc(100% - 40px - 51px - 51px);
  padding: 20px;
  background: var(--color-border-1);
  overflow-y: scroll;
}

.layout-body.hidden-menu {
  left: 81px;
  width: calc(100% - 121px);
}

.layout-footer {
  width: calc(100% - 221px);
  left: 221px;
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
            