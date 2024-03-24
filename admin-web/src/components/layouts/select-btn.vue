<template>
    <div class="layout-nav-btn ml20 " @click="onConfig">
        <div class="icon icon-shezhi"></div>
    </div>
    <selectModel ref="selectModelRef"></selectModel>
</template>

<script lang="ts">
export default {
    name: "select-layout-btn",
};
</script>

<script setup lang="ts">
import { getCurrentInstance, ref, onMounted, watch } from "vue";
import selectModel from "@/components/layouts/select.vue";

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const selectModelRef = ref<HTMLElement>()

const onConfig = () => {
    proxy?.$refs['selectModelRef']?.open()
}

</script>
<style scoped>
.layout-nav-btn {
    cursor: pointer;
    color: var(--color-text-1)
}
</style>