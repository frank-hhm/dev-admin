<template>
    <router-view />
</template>

<script lang="ts" setup>
import { getCurrentInstance, onMounted, watch, onBeforeUnmount } from "vue";
import { storeToRefs } from "pinia";
import { useAdminStore, useAppStore, useEnumStore, useWebsocketStore } from "@/store";

const { systemInfo, isDark } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

useEnumStore().initEnum();

useAppStore().getSystemInfo();
useAppStore().setDark(isDark.value)
const listenResizeFn = (e: any) => {
    useAppStore().setMobile($utils.isMobileOrSmallScreen())
}

onMounted(() => {
    $utils.eventsListener('resize', listenResizeFn,true);
    if (useAdminStore().token) {
        useAdminStore().initInfo()
    }
    setHeadLinks()
});
onBeforeUnmount(() => {
    $utils.dispatchEvents('resize', listenResizeFn);
})

const setHeadLinks = () => {
    const link = document.createElement('link');
    link.rel = 'icon';
    link.type = 'image/x-icon';
    link.href = systemInfo.value.system_icon || '';
    document.head.appendChild(link);
}

watch(
    () => systemInfo.value,
    (val) => {
        setHeadLinks()
    },
    { deep: true }
);
</script>



<style>
#nprogress .bar {
    background: rgba(var(--primary-6)) !important;
}

#nprogress .peg {
    box-shadow: 0 0 10px rgba(var(--primary-6)), 0 0 5px rgba(var(--primary-6)) !important;
}
</style>