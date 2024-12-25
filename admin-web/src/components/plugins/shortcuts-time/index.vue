<template>
    <a-range-picker show-time :shortcuts="shortcuts" shortcuts-position="left" :format="format" @change="onChange"
            @select="onSelect" @ok="onOk" style="width: 100%;"/>
</template>
<script lang="ts">
export default {
    name: "shortcuts-time",
};
</script>
<script setup lang="ts">
import { ref, getCurrentInstance, watch } from "vue";

const { proxy } = getCurrentInstance() as any;

const props = withDefaults(
    defineProps<{
        modelValue?: string | number | string[] | number[] | any;
        shortcuts?: boolean;
        btnShortcuts?: boolean;
        format?: string;
        size?: string;
    }>(),
    {
        modelValue: "",
        shortcuts: true,
        btnShortcuts: true,
        format: "YYYY-MM-DD HH:mm:ss",
        size: "default",
    }
);

const _modelValue = ref<string | number | string[] | number[] | any>(
    props.modelValue
);

const emit = defineEmits(["change", "update:modelValue"]);

interface shortcutsItem {
    label: string;
    value: () => any;
}

let now = new Date();
let nowTime = now.getTime(); //当前时间戳
let nowDay = now.toLocaleDateString(); //当天
let nowDayTime = new Date(nowDay).getTime(); //当天开始时间戳
let nowMonth = now.getMonth(); //当前月
let nowYear = now.getFullYear(); //当前年

const shortcuts = ref<shortcutsItem[]>([
    {
        label: '昨天',
        value: () => {
            const start = nowDayTime - 3600 * 1000 * 24;
            const end = nowDayTime - 1;
            return [start, end];
        },
    },
    {
        label: '今天',
        value: () => {
            const end = nowDayTime + 3600 * 1000 * 24 - 1;
            return [nowDayTime, end];
        },
    }, {
        label: '最近3天',
        value: () => {
            const start = nowDayTime - 3600 * 1000 * 24 * 2;
            const end = nowDayTime + 3600 * 1000 * 24 - 1;
            return [start, end];
        },
    },
    {
        label: '最近7天',
        value: () => {
            const start = nowDayTime - 3600 * 1000 * 24 * 6;
            const end = nowDayTime + 3600 * 1000 * 24 - 1;
            return [start, end];
        },
    },
    {
        label: '本月',
        value: () => {
            let start = new Date(nowYear, nowMonth, 1).getTime(); //本月开始时间戳
            let end = new Date(nowYear, nowMonth + 1, 0).getTime() + 3600 * 1000 * 24 - 1; // 本月结束时间戳
            return [start, end];
        },
    },
    {
        label: '今年',
        value: () => {
            let start = new Date(nowYear, 0).getTime();
            let end = nowTime;
            return [start, end];
        },
    },
],);

const onChange = (dateString: any, date: any) => {
    console.log('onChange: ', dateString, date);
    emit("change", dateString);
    emit("update:modelValue", dateString);
}

const onSelect = (dateString: any, date: any) => {
    // console.log('onSelect: ', dateString, date);
    // emit("change", dateString);
    // emit("update:modelValue", dateString);
}
const onOk = (dateString: any, date: any) => {
    console.log('onOk: ', dateString, date);
    emit("change", dateString);
    emit("update:modelValue", dateString);
}
</script>