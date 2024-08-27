<template>
    <template v-for="(item, index) in menuList" :key="index">
        <template v-if="item.children">
            <a-sub-menu class="layout-side-sub-item" :key="item.menu_path" :index="item.menu_path">
                <template #title>
                    <i class="icon mr10" v-if="item.icon" :class="item.icon"></i>
                    <span>{{ item.menu_name }}</span>
                </template>
                <LayoutSideMenu :data="item.children" ref="LayoutSideMenuRef" :name="item.menu_name"></LayoutSideMenu>
            </a-sub-menu>
        </template>
        <template v-else>
            <a-menu-item :key="item.menu_path" :route="item.menu_path + (item.params ? '?' + item.params : '')"
                class="layout-side-item" :class="cur.path == item.menu_path ||
        (parentCur.meta.parent && parentCur.meta.parent == item.menu_path) ||
        item.menu_path == $utils.getMenuString(cur.path) ||
        $utils.getMenuActionParent(item, cur.path)
        ? 'is-active'
        : ''
        " v-permission="item.menu_node" @click="goPath(item)" :index="item.menu_path">
                <i class="icon mr10" v-if="item.icon" :class="item.icon"></i>
                <span class="layout-side-item-title">{{ item.menu_name }}</span>
            </a-menu-item>
        </template>
    </template>
</template>
<script lang="ts">
export default {
    name: "LayoutSideMenu",
};
</script>
<script setup lang="ts">
import { defineProps, ref, getCurrentInstance, onMounted, watch, nextTick } from "vue";
import { useRouter, useRoute } from "vue-router";
import LayoutSideMenu from "./LayoutSideMenu.vue";
import { MenusItemType, MenusListsType } from '@/types';

const {
    proxy,
    proxy: { $message, $utils },
} = getCurrentInstance() as any;

const router = useRouter();

const cur = ref<any>(router.currentRoute);

const parentCur = ref<any>(router.currentRoute);

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

// 刷新菜单数据
const refreshMenuList = (data: MenusListsType) => {
    menuList.value = [];
    nextTick(() => {
        menuList.value = data;
    })
    setTimeout(() => {

    }, 100);
};

const goPath = (menu: any) => {
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
.layout-side-item {
    /* margin: 5px 10px; */
    /* height: 40px; */
    min-width: 100px !important;
    border-radius: 2px;
    -webkit-transition: all 0.1s ease-in-out;
    transition: all 0.1s ease-in-out;
}

.layout-side-sub-item>div {
    -webkit-transition: all 0.1s ease-in-out;
    transition: all 0.1s ease-in-out;
}

.layout-side-item-title{
    user-select: none;
}
.layout-side-item.is-active {}
</style>