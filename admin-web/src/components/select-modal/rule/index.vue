<template>
    <div>
        <a-modal title="接口列表" :width="isMobile ? 'calc(100% - 20px)' : '1000px'" :top="useSetting().ModalTop" class="modal" v-model:visible="visible"
            :align-center="false" title-align="start" @BeforeCancel="close">
            <div class="rule-select-body" :class="isMobile ? 'mobile' : ''">
                <div class="mt10 mb10 flex">
                    <a-input placeholder="搜索名称" v-model="search_value">
                        <template #prefix>
                            <icon-search />
                        </template>
                    </a-input>
                </div>
                <div class="rules-main">
                    <div class="rules-list">
                        <template v-for="(item, index) in ruleLists" :key="index">
                            <div class="rules-item" v-if="search_value == '' || item.search === true"
                                @click="selectItem(item)">
                                <div>接口名称：{{ item.title }}</div>
                                <div>请求方式：{{ item.method }}</div>
                                <div>接口地址：{{ item.rule }}</div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </a-modal>
    </div>
</template>
<script lang="ts">
export default {
    name: "select-rule-modal",
};
</script>
<script setup lang="ts">
import { ref, getCurrentInstance, watch } from "vue";
import { getRuleListMenusApi } from "@/api/system/menus";
import { Result, ResultError } from "@/types";
import { Message } from '@arco-design/web-vue';
import { useSetting } from "@/hooks/useSetting";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";

const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const emit = defineEmits(["change"]);

const modalStatus = ref<boolean>(false);
const visible = ref<boolean>(false);
const loadMessage = ref();

const open = () => {
    if (modalStatus.value == true) {
        return;
    }
    modalStatus.value = true;
    loadMessage.value = Message.loading({
        duration: 0,
        content: "正在加载路由接口...",
    });
    setTimeout(() => {
        initData();
    }, 1000);
};

const ruleLists = ref<any>([]);

const initData = () => {
    search_value.value = '';
    getRuleListMenusApi()
        .then((res: Result) => {
            visible.value = true;
            ruleLists.value = res.data;
            loadMessage.value.close();
        })
        .catch((err: ResultError) => {
            loadMessage.value.close();
            if (err.message && typeof err.message == "string") {
                $utils.errorMsg(err.message);
            }
        });
};

const search_value = ref<string>('')

const close = () => {
    modalStatus.value = false;
    visible.value = false;
    search_value.value = '';
    return true
};

const selectItem = (item: any) => {
    emit("change", item);
    visible.value = false;
    modalStatus.value = false;
};

watch(
    () => search_value.value,
    (val) => {
        ruleLists.value?.forEach((item: any) => {
            item.search = item.rule.indexOf(val) !== -1 || item.title.indexOf(val) !== -1 ? true : false;
        });
    }
);

defineExpose({ open });
</script>
<style >
.rule-select-body .rules-main{
    height: 450px;
    overflow: hidden;
    overflow-y: scroll;
}

.rule-select-body .rules-list {
    display: flex;
    flex-wrap: wrap;
}

.rule-select-body .rules-item {
    width: calc(33.33% - 34px);
    margin: 5px;
    background: var(--color-bg-1);
    border-radius: 5px;
    padding: 10px;
    border: 2px solid var(--color-border-1);
    cursor: pointer;
    height: 60px;
    white-space: nowrap;
}

.rule-select-body .rules-item:hover {
    border-color: rgba(var(--primary-6));
}
.rule-select-body.mobile .rules-list{
    display: block;
}
.rule-select-body.mobile  .rules-item{
    width: calc(100% - 20px);
    margin:5px 0;
}
</style>