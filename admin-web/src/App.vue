<template>
    <router-view />
</template>
    
<script lang="ts" setup>
import { onMounted, watch } from "vue";
import { storeToRefs } from "pinia";
import { useAdminStore, useAppStore, useEnumStore, useWebsocketStore } from "@/store";

const { systemInfo,isDark } = storeToRefs(useAppStore());

useEnumStore().initEnum();

useAppStore().getSystemInfo();
useAppStore().setDark(isDark.value)

onMounted(() => {
    if (useAdminStore().token) {
        useAdminStore().initInfo()
        // useWebsocketStore().initWebSocket();
    }
    document.title = import.meta.env.VITE_BASE_SYSTEM_NAME;
    setHeadLinks()
});

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
    
    
    