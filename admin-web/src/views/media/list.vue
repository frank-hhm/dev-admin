<template>
    <layout-body-tabs :tabs="headTab" v-model="tabActive" v-model:loading="initLoading" heightFil @change="changeTab">
        <media ref="mediaRef"></media>
    </layout-body-tabs>
</template>
<script lang="ts" setup>
import media from "@/components/media/list.vue";
import { EnumType } from "@/types";
import { onMounted, ref, getCurrentInstance } from "vue";

const { proxy } = getCurrentInstance() as any

const headTab = ref<EnumType>([
    {
        name: "图片",
        value: 'image',
    },
    {
        name: "视频",
        value: 'video',
    },
    {
        name: "音频",
        value: 'audio',
    }
]);

const initLoading = ref<boolean>(true);

const tabActive = ref<string>('image');

const changeTab = (tab: string) => {
    initLoading.value = true;
    tabActive.value = tab;
    proxy?.$refs['mediaRef']?.toTypeInit(tab,initCallback);
};

const mediaRef = ref<HTMLElement>()

const initCallback = () => {
    setTimeout(() => {
        initLoading.value = false;
    }, 300);
}

onMounted(() => {
    proxy?.$refs['mediaRef']?.open([],initCallback);
})

</script>
  