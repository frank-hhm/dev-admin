<template>
    <div class="layout-body-main">
        <div class="layout-body-main-header">
            <slot name="header"></slot>
        </div>
        <div class="layout-body-main-content" :style="{ height: contentHeight + 'px' }">
            <slot name="content" :height="contentHeight"></slot>
        </div>

        <div class="layout-body-main-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>
<script lang="ts">
export default {
    name: "LayoutBodyContent",
};
</script>
<script lang="ts" setup>
import { onMounted } from "vue";
import { ref } from "vue";

const contentHeight = ref<number>(0)

onMounted(() => {
    var fixedTable: any = document?.getElementsByClassName("layout-body-main-content");
    let parentNodes = fixedTable[0]?.parentNode, childrens = parentNodes.children;
    let footerHeight = 0, headerHeight = 0;
    for (let index = 0; index < childrens.length; index++) {
        if (childrens[index].classList.contains('layout-body-main-header')) {
            headerHeight = childrens[index].offsetHeight;
            console.log(1111)
        }
        if (childrens[index].classList.contains('layout-body-main-footer')) {
            footerHeight = childrens[index].offsetHeight - 24;
            console.log(2222)
        }
    }
    console.log(parentNodes.offsetHeight, headerHeight, footerHeight)
    let _contentHeight = parentNodes.offsetHeight - headerHeight - footerHeight
    contentHeight.value = _contentHeight;
});
</script>

<style scoped>
.layout-body-main {
    height: 100%;
    position: relative;
    overflow: hidden;
}

.layout-body-main-footer {
    display: flex;
    justify-content: end;
    width: calc(100% - 2 * var(--base-padding));
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: var(--color-bg-3);
    padding: var(--base-padding);
    z-index: 999;
}

.layout-body-main-content {
    background-color: var(--color-bg-3);
    padding: var(--base-padding);
}
</style>