<template>
  <template v-for="(item, index) in menus" :key="index">
    <div class="layout-side-item" :class="curNav == item.id ? 'active' : ''" @click="onPath(item)">
      <div class="icon" :class="item.icon"></div>
      <div class="name">{{ item.menu_name }}</div>
    </div>
  </template>
</template>
<script lang="ts">
export default {
  name: "LayoutSideMenu",
};
</script>
<script setup lang="ts">
import {
  ref,
  getCurrentInstance,
  onMounted,
  watch,
  nextTick,
} from "vue";
import { useRouter, useRoute } from "vue-router";
import { useMenusStore } from "@/store";
import { storeToRefs } from "pinia";

const { menus } = storeToRefs(useMenusStore());
const {
  proxy: { $utils },
} = getCurrentInstance() as any;

const router = useRouter();

const route = useRoute();

const emit = defineEmits(["change"]);

const curNav = ref<number | string>(-1);
const onPath = (menu: any) => {
  curNav.value = menu.id;
  if (menu.children && Object.keys(menu.children).length > 0) {
    emit("change", menu.children);
    // 存在下级
    let itemMenu = $utils.getItemPath(menu.children);
    router.push({
      path: itemMenu.menu_path,
      query: itemMenu.url_params || {},
    });
  } else {
    emit("change", []);
    // 直接跳转指定页面
    router.push({
      path: menu.menu_path,
      query: menu.url_params || {},
    });
  }
};
const initRoute = () => {
  var _router = {
    path: router.currentRoute.value.path,
    activeMenu: router.currentRoute.value.meta?.activeMenu || "",
  };
  if (!menus.value || menus.value == undefined) {
    router.replace({
      path: "/",
    });
    return false;
  }
  refresh(menus.value, _router, -1);
};

//
const refresh = (data: any, _router: any, parent: number | string) => {
  try {
    data.forEach((item: any, index: any) => {
      if (item.children && item.children.length > 0) {
        refresh(item.children, _router, parent == -1 ? item.id : parent);
      } else if (
        item.menu_path == _router.path ||
        (_router.activeMenu != "" && item.menu_path == _router.activeMenu) ||
        $utils.getMenuString(item.menu_path, true) ==
        $utils.getMenuString(_router.path, true) ||
        $utils.getMenuActionParent(item, _router.path)
      ) {
        if (parent === -1) {
          setCurNavPath(item.id, false);
        } else {
          setCurNavPath(parent, false);
        }
        throw new Error("End refresh");
      }
    });
  } catch (e: any) {
    // if(e.message == 'End Loop') throw e
  }
};

const setCurNavPath = (id: number | string, addon: string | unknown) => {
  curNav.value = id;
  menus.value.forEach((item: any) => {
    if (id === item.id) {
      var children = item.children || [];
      emit("change", children);
    }
  });
};

watch(
  () => router.currentRoute.value,
  (val) => {
    initRoute();
  },
  { deep: true }
);

watch(
  () => menus.value,
  (val) => {
    menus.value = val;
    nextTick(() => {
      initRoute();
    });
  },
  { deep: true }
);

onMounted(() => {
  nextTick(() => {
    initRoute();
  });
});
</script>
<style scoped>
.layout-side-item {
  width: 40px;
  /* height: 30px; */
  height: 40px;
  text-align: center;
  padding: 10px;
  cursor: pointer;
  border-radius: var(--border-radius-medium);
  color: var(--color-text-1);
  margin-bottom: 10px;
  user-select: none;
  position: relative;
}

.layout-side-item .icon {
  font-size: 24px;
  font-weight: 400;
  line-height: 24px;
}

.layout-side-item .name {
  font-size: 12px;
  line-height: 20px;
  display: flex;
  justify-content: center;
  white-space: nowrap;
}

.layout-side-sub-item>div {
  margin: 0 10px;
  /* height: 40px; */
}

.layout-side-item.active {
  color: rgb(var(--primary-6));
  background: var(--color-fill-2);
}

.layout-side-item:hover {
  color: rgb(var(--primary-6));
  background: var(--color-fill-2);
}
</style>