<template>
    <div class="layout-body-main">
        <div class="layout-body-main-header">
            <slot name="header"></slot>
        </div>
        <div class="layout-body-main-content" :style="[
            contentHeight ? `height:${contentHeight}px` : ''
        ]">
            <slot name="content" :height="contentHeight - footerHeight + 10"></slot>
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
import { nextTick, ref } from "vue";

const contentHeight = ref<number>(0)

const footerHeight = ref<number>(0)

onMounted(() => {
    nextTick(() => {
        setTimeout(() => {
            var fixedTable: any = document?.getElementsByClassName("layout-body-main-content");
            let parentNodes = fixedTable[0]?.parentNode, childrens = parentNodes.children;
            let headerHeight = 0;
            for (let index = 0; index < childrens.length; index++) {
                if (childrens[index].classList.contains('layout-body-main-header')) {
                    headerHeight = childrens[index].offsetHeight;
                }
                if (childrens[index].classList.contains('layout-body-main-footer')) {
                    footerHeight.value = childrens[index].offsetHeight;
                }
            }
            let _contentHeight = parentNodes.offsetHeight - headerHeight - 12 * 3 - 2;
            console.log(_contentHeight, footerHeight.value)
            contentHeight.value = _contentHeight;
        }, 300);
    })
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
    width: calc(100% - 2 * var(--base-padding) - 2px);
    position: absolute;
    bottom: 1px;
    left: 1px;
    right: 1px;
    background-color: var(--color-bg-2);
    padding: var(--base-padding);
    z-index: 999;
}

.layout-body-main-content {
    background-color: var(--color-bg-2);
    padding: var(--base-padding);
    border-radius: var(--base-radius-default);
    border: 1px solid var(--color-border-1);
}
</style>