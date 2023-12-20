<template>
    <div>
        <a-pagination v-model:current="listPage.page" v-model:page-size="listPage.limit" :total="props.listPage.total"
            @change="changeCurPage" @page-size-change="changePageLimit" size="mini" show-total show-page-size />
    </div>
</template>
<script lang="ts">
export default {
    name: "page",
};
</script>
<script lang="ts" setup>
import { ref, getCurrentInstance, watch } from "vue";
import { PageLimitType } from "@/types";
import { useSetting } from "@/hooks/useSetting";

const props = withDefaults(
    defineProps<{
        modelValue?: string | number | string[] | number[] | any;
        listPage: PageLimitType;
    }>(),
    {
        modelValue: "",
        listPage: () => {
            return {
                total: 0,
                page: 1,
                limit: useSetting().PageLimit.value || 10,
            };
        },
    }
);
const emit = defineEmits(["change"]);

const total = ref<number | string>(props.listPage.total);

const page = ref<number | string>(props.listPage.page);

const limit = ref<number>(useSetting().PageLimit.value || 10);

const changePageLimit = (val: number) => {
    useSetting().setPageLimit(val);
    limit.value = val;
    refresh();
};

const changeCurPage = (val: number) => {
    console.log(val)
    page.value = val;
    refresh();
};

const refresh = () => {
    emit("change", {
        total: total.value,
        limit: limit.value,
        page: page.value,
    });
};

const updateChangeCurPage = (res: any) => { };

const updateChangePageLimit = (res: any) => {
    console.log(res);
};

watch(
    () => props.listPage,
    (val) => {
        total.value = props.listPage.total;
        page.value = props.listPage.page;
    },
    {
        deep: true,
    }
);
</script>                                                