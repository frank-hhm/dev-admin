<template>
    <div class="layout-body-main">
        <div class="layout-body-main-header" v-if="header">
            <slot name="header"></slot>
        </div>
        <div class="layout-body-main-content" :style="[
            contentHeight ? `height:${contentHeight - footerHeight}px` : ''
        ]" :class="[
            hideContentPadding ? 'hide-content-padding' : '',
            hideFooter ? 'hide-footer' : ''
        ]">
            <div class="layout-body-main-content-page-header" v-if="pageHeader">
                <div class="page-header-left">
                    <slot name="page-header-left"></slot>
                </div>
                <div class="page-header-center">
                    <slot name="page-header-center"></slot>
                </div>
                <div class="page-header-right">
                    <slot name="page-header-right"></slot>
                </div>
            </div>
            <div class="layout-body-main-content-body" :style="[
            contentHeight ? `height:${contentBodyHeight}px` : ''
        ]">
                <slot name="content" :height="contentBodyHeight"></slot>
            </div>
        </div>

        <div class="layout-body-main-footer" v-if="!hideFooter" :class="[
            footerTopBorder ? 'footer-top-border' : ''
        ]">
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

const props = withDefaults(
    defineProps<{
        hideContentPadding?: boolean;
        footerTopBorder?: boolean;
        pageHeader?: boolean;
        header?: boolean;
        hideFooter?: boolean;
    }>(),
    {
        hideContentPadding: false,
        footerTopBorder: true,
        header: false,
        pageHeader: false,
        hideFooter: false,
    }
);

const contentHeight = ref<number>(0)

const contentBodyHeight = ref<number>(0)

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
            let _contentHeight = parentNodes.offsetHeight - headerHeight;

            if (props.header) {
                contentHeight.value = _contentHeight - 12;
            } else {
                contentHeight.value = _contentHeight;
            }
            if (props.hideFooter) {
                footerHeight.value = 0
            }
            contentBodyHeight.value = contentHeight.value - footerHeight.value - (props.hideContentPadding ? 0 : 24) - (props.pageHeader ? 49 : 0)
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
.layout-body-main .layout-body-main-header{
    margin-bottom: var(--base-padding);
}
.layout-body-main-content-page-header {
    height: 45px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--color-border-1);
    padding: 0 var(--base-padding);
}

.layout-body-main-footer {
    display: flex;
    justify-content: end;
    align-items: center;
    width: calc(100% - 2 * var(--base-padding) - 2px);
    position: absolute;
    bottom: 1px;
    left: 1px;
    right: 1px;
    background-color: var(--color-bg-2);
    padding: var(--base-padding);
    z-index: 999;
    overflow: hidden;
}

.layout-body-main-footer.footer-top-border {
    border-top: 1px solid var(--color-border-1);
    border-bottom-left-radius: var(--base-radius-default);
    border-bottom-right-radius: var(--base-radius-default);
}

.layout-body-main-content {
    height: 100%;
    background-color: var(--color-bg-2);
    border-top-left-radius: var(--base-radius-default);
    border-top-right-radius: var(--base-radius-default);
    border: 1px solid var(--color-border-1);
    overflow: hidden;
}

.layout-body-main-content.hide-footer {
    border-bottom-left-radius: var(--base-radius-default);
    border-bottom-right-radius: var(--base-radius-default);
}

.layout-body-main-content .layout-body-main-content-body {
    max-height: 100%;
    padding: var(--base-padding);
    background-color: var(--color-bg-2);
    overflow-y: scroll;
}

.layout-body-main-content .layout-body-main-content-body::-webkit-scrollbar {
    width: 0;
}

.layout-body-main-content .layout-body-main-content-body::-webkit-scrollbar-track {
    background: none
}

.layout-body-main-content .layout-body-main-content-body::-webkit-scrollbar-thumb {
    background: none
}

.layout-body-main-content.hide-content-padding .layout-body-main-content-body {
    padding: 0;
    /* border: none; */
}

.layout-body-main-content .page-header-left {
    white-space: nowrap;
    color: var(--color-text-1)
}
</style>