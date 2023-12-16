<template>
    <layout-body-tabs :tabs="headTab" v-model="tabActive" heightFil @change="changeTab">
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
    }
]);

const tabActive = ref<string>('image');

const changeTab = (tab: string) => {
    tabActive.value = tab;
};

const mediaRef = ref<HTMLElement>()

onMounted(() => {
    proxy?.$refs['mediaRef']?.open();
})

</script>
  