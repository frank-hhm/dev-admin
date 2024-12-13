<template>
    <defaultLayout v-if="layout == 'default'"></defaultLayout>
    <layout1 v-if="layout == 'layout1'">
    </layout1>
</template>
<script lang="ts" setup>
import defaultLayout from "./default/Layout.vue";
import layout1 from "./layout1/Layout.vue";
import { useAppStore } from "@/store";
import { storeToRefs } from "pinia";

const { layout } = storeToRefs(useAppStore());

</script>