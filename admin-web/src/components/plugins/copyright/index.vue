
<template>
    <div class="footer-main" :style="[`bottom:${bottom}`, `font-size:${fontSize}`, `color:${color}`]">
        <div class="footer-copyright">
            <span>Copyright © {{ year }}.</span>
            <div class="footer-btn ml5" @click="onLink">{{ systemInfo?.copyright || copyright }}</div>
            <span class="ml5">Inc. ®</span>
        </div>
    </div>
</template>

<script lang="ts">
export default {
    name: "copyright",
};
</script>
<script lang="ts" setup>
import { ref } from "vue";
import { useAppStore } from '@/store';
import { storeToRefs } from 'pinia';

const { systemInfo } = storeToRefs(useAppStore());

const copyright = ref<string>(import.meta.env.VITE_BASE_COPYRIGHT);

const props = withDefaults(
    defineProps<{
        marginTop?: string;
        bottom?: string;
        color?: string;
        fontSize?: string;
    }>(),
    {
        color: "var(--color-text-3)",
        marginTop: "0px",
        bottom: "0px",
        fontSize: "12px",
    }
);

const year = new Date().getFullYear();

const onLink = () => {
    window.open("https://www.dev-frank.cn", '_blank');
}
</script>

<style scoped>
.footer-main {
    position: absolute;
    width: 100%;
    text-align: center;
    padding: 5px 0;
    z-index: 99;
}

.footer-copyright {
    margin-top: 10px;
    display: flex;
    justify-content: center;
}

.footer-btn {
    cursor: pointer;
}

.footer-btn:hover {
    color: var(--base-default);
}
</style>