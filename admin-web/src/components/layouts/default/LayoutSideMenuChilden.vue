<template>
  <template v-for="(item, index) in menuList" :key="index">
    <template v-if="item.children">
      <div class="layout-side-childen-sub">
        <div class="layout-side-childen-sub-title">
          <i class="icon mr5" v-if="item.icon" :class="item.icon"></i>
          <span class="">{{ item.menu_name }}</span>
        </div>
        <LayoutSideMenuChilden v-if="!initMenuLoading" :data="item.children" :name="item.menu_name">
        </LayoutSideMenuChilden>
      </div>
    </template>
    <template v-else>
      <div :key="item.id" class="layout-side-childen-item" :class="cur.path == item.menu_path ||
    cur?.meta?.activeMenu == item.menu_path ||
    item.menu_path == $utils.getMenuString(cur.path) ||
    $utils.getMenuActionParent(item, cur.path)
    ? 'active'
    : ''
    " v-permission="item.menu_node" :index="item.menu_path" @click="onPath(item)">
        <i class="icon mr5" v-if="item.icon" :class="item.icon"></i>
        <span class="layout-side-childen-item-title">{{ item.menu_name }}</span>
      </div>
    </template>
  </template>
</template>
<script lang="ts">
export default {
  name: "LayoutSideMenuChilden",
};
</script>
<script setup lang="ts">
import { defineProps, ref, getCurrentInstance, onMounted, watch, nextTick, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import LayoutSideMenuChilden from "./LayoutSideMenuChilden.vue";

const {
  proxy: { $message, $utils },
} = getCurrentInstance() as any;

const router = useRouter();

const route = useRoute();

const props = withDefaults(
  defineProps<{
    data?: any;
    name?: string;
  }>(),
  {
    data: [],
    name: "",
  }
);

const menuList = ref<any>(props.data);

const cur = ref<any>(router.currentRoute);

const initMenuLoading = ref<boolean>(false)

// 刷新菜单数据
const refreshMenuList = (data: any) => {
  initMenuLoading.value = true;
  nextTick(() => {
    initMenuLoading.value = false;
    menuList.value = data;
  })
};

const onPath = (menu: any) => {
  router.push({
    path: menu.menu_path,
    query: menu.url_params || {},
  });
};

watch(
  () => router.currentRoute.value.path,
  (val) => {
    cur.value.path = val;
  },
  { deep: true }
);

onMounted(() => { });

defineExpose({ refreshMenuList });
</script>
<style scoped>
.layout-side-childen-sub {
  user-select: none;
}

.layout-side-childen-sub-title {
  padding: 0 10px;
  height: 38px;
  line-height: 38px;
  color: var(--color-text-3);
  font-size: 12px;
}

.layout-side-childen-item {
  width: calc(100% - 20px);
  height: 38px;
  line-height: 38px;
  display: inline-block;
  align-items: center;
  user-select: none;
  cursor: pointer;
  text-align: left;
  padding: 0 10px;
  font-size: 12px;
  border-radius: 5px;
  color: var(--color-text-1);
}

.layout-side-childen-sub .layout-side-childen-item {
  padding: 0 10px;
}

.layout-side-childen-sub .icon,
.layout-side-childen-item .icon {
  font-size: 12px;
}

.layout-side-childen-item-title {
  font-size: 12px;
  user-select: none;
}

.layout-side-childen-item.active {
  color: rgba(var(--primary-6));
  background: var(--color-fill-2);
}

.layout-side-childen-item:hover {
  color: rgba(var(--primary-6));
}
</style>