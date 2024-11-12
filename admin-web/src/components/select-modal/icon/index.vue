<template>
    <a-dropdown trigger="click" v-model:popup-visible="visible" ref="iconDropdown">
        <span></span>
        <template #content>
            <div class="select-icon-modal" :class="isMobile ? 'mobile' : ''">
                <div class="select-icon-search">
                    <a-input v-model="searchInput">
                        <template #prefix>
                            <icon-search />
                        </template>
                    </a-input>
                </div>
                <div class="select-icon-list">
                    <template v-for="(item, index) in iconLists" :key="index">
                        <template v-if="index + 1 == curPage">
                            <template v-for="(items, idx) in item" :key="idx">
                                <a-tooltip>
                                    <template #content>
                                        <i class="select-icon-item-tip icon" :class="'icon-' + items.font_class"></i>
                                    </template>
                                    <div class="select-icon-item" @click="selectItem(items)">
                                        <i class="icon" :class="'icon-' + items.font_class"></i>
                                    </div>
                                </a-tooltip>
                            </template>
                        </template>
                    </template>
                    <div v-if="iconLists.length == 0" class="select-icon-empty">
                        无数据
                    </div>
                </div>
                <div class="select-icon-page">
                    <div class="select-icon-page-count">
                        <span id="select-icon-page-current">{{ curPage }}</span>
                        /<span id="select-icon-page-pages">{{ countPage }}</span> (共<span
                            id="select-icon-page-length">{{
        total }}</span>个)
                    </div>
                    <div class="select-icon-page-operate">
                        <div class="select-icon-close"></div>
                        <a-button size="small" @click="close">关闭</a-button>
                        <a-button class="ml10" size="small" @click="prevClick"
                            :disabled="curPage == 1 || countPage == 0">
                            <icon-left /></a-button>
                        <a-button class="ml10" size="small" @click="nextClick"
                            :disabled="countPage == curPage || countPage == 0"><icon-right /></a-button>
                    </div>
                </div>
            </div>
        </template>
    </a-dropdown>
</template>
<script lang="ts">
export default {
    name: "select-icon-modal",
};
</script>
<script setup lang="ts">
import { getCurrentInstance, ref, watch } from "vue";
import iconData from "./icon.json";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";

const { isMobile } = storeToRefs(useAppStore());
const {
    proxy,
} = getCurrentInstance() as any;

const iconDropdown = ref<HTMLElement>();

const visible = ref<boolean>(false)

const emit = defineEmits(["change"]);

const open = () => {
    init();
    visible.value = true;
};
const close = () => {
    visible.value = false;
};

const init = () => {
    searchInput.value = "";
    curPage.value = 1;
};

// 查询
const searchInput = ref<string>("");
const total = ref<number>(iconData.length);

//分页数据
const pageLimit = 24;
//总页数
const countPage = ref<number>(
    total.value % pageLimit === 0
        ? total.value / pageLimit
        : parseInt(String(total.value / pageLimit + 1))
);
//当前分页
const curPage = ref<number>(1);

//上一页
const prevClick = () => {
    if (curPage.value > 1 && countPage.value > 0) {
        curPage.value--;
    }
};
//下一页
const nextClick = () => {
    if (curPage.value < pageLimit && countPage.value > 0) {
        curPage.value++;
    }
};

//当前分页数据
const iconLists: any = ref([]);

//当前总数据
const icons: any = ref([]);

const getIcons = () => {
    iconLists.value = [];
    for (var i = 0; i < countPage.value; i++) {
        // 按limit分块
        let lm: any = [];
        for (
            var j = i * pageLimit;
            j < (i + 1) * pageLimit && j < total.value;
            j++
        ) {
            lm.push(icons.value[j]);
        }
        iconLists.value.push(lm);
    }
};

const getData = () => {
    icons.value = [];
    for (var i = 0; i < iconData.length; i++) {
        var obj: any = iconData[i];
        // 判断是否模糊查询
        if (
            searchInput.value &&
            obj?.font_class.indexOf(searchInput.value) === -1 &&
            obj.name.indexOf(searchInput.value) === -1
        ) {
            continue;
        }
        icons.value.push(obj);
    }
    total.value = icons.value.length;
    countPage.value =
        total.value % pageLimit === 0
            ? total.value / pageLimit
            : parseInt(String(total.value / pageLimit + 1));
    curPage.value = 1;
    getIcons();
};

getData();

watch(
    () => searchInput.value,
    (val) => {
        getData();
    },
    { deep: true }
);

const selectItem = (item: any) => {
    emit("change", 'icon-' + item.font_class);
    close()
}

defineExpose({ open });
</script>
<style scoped>
.select-icon-modal {
    width: 320px;
    height: 198px;
    overflow: hidden;
}
.select-icon-modal.mobile{
    width: 260px;
}

.select-icon-search {
    padding: 10px 10px 5px;
}

.select-icon-page {
    border-top: 1px solid var(--color-border-1);
    height: 30px;
    line-height: 30px;
    padding: 10px 0 0;
    margin: 5px 10px 0;
}

.select-icon-page .select-icon-page-count {
    display: inline-block;
    font-size: 12px;
    color: var(--color-text-3);
}

.select-icon-page .select-icon-page-operate {
    display: flex;
    float: right;
    cursor: default;
}

.select-icon-list {
    margin: 0 5px;
    height: 108px;
}

.select-icon-item {
    display: inline-block;
    width: calc(12.4% - 10px);
    line-height: 26px;
    text-align: center;
    cursor: pointer;
    vertical-align: top;
    height: 26px;
    margin: 4px;
    border: 1px solid var(--color-border-1);
    border-radius: 2px;
    color: var(--color-text-1);
}

.select-icon-item .icon {
    font-size: 14px;
}

.select-icon-empty {
    text-align: center;
    line-height: 60px;
    color: var(--color-text-3);
}

.select-icon-item:hover {
    border-color: rgba(var(--primary-6));
    background: rgba(var(--primary-6));
    color: var(--color-white);
}

.select-icon-item-tip {
    font-size: 40px;
}
</style>