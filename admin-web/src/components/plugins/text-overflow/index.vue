<template>
    <div class="text-overflow" :style="[
        `width:${typeof width === 'number' ? width + 'px' : width}`,
        Number(number) > 1 ? `-webkit-line-clamp:${number}` : ''
    ]" :class="[
        Number(number) > 1 ? 'overflow-webkit' : ''
    ]">
        {{ text }}
    </div>
</template>
<script lang="ts">
export default {
    name: "textOverflow",
};
</script>
<script lang="ts" setup>
import { ref } from "vue";

const props = withDefaults(
    defineProps<{
        width?: number | string;
        text?: string;
        number?: number | string;
    }>(),
    {
        text: "",
        width: '100%',
        number: 1
    }
);
</script>
<style scoped>
.text-overflow {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.text-overflow.overflow-webkit {
    display: -webkit-box;
    -webkit-box-orient: vertical;
}
</style>