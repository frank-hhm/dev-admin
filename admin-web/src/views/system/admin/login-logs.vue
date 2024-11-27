<template>
    <div>
        <a-drawer class="drawer-default" title="登录记录" append-to-body :width="isMobile ? 'calc(100% - 20px)' : '600px'"
            v-model:visible="visible" @BeforeClose="close">
            <div>
                <a-table :loading="initLoading" :data="lists" row-key="id" isLeaf :pagination="false">
                    <template #columns>
                        <a-table-column title="登录IP" data-index="last_ip" align="center" :width="140">
                            <template #cell="{ record }">
                                <div>{{ record.ip }}</div>
                            </template>
                        </a-table-column>
                        <a-table-column title="登录状态" data-index="status" align="center" :width="100">
                            <template #cell="{ record }">
                                <div :class="`text-${record.status == 1 ? 'green' : 'red'}`">{{ record.status ==
            1 ? '登录成功' : '登录失败' }}</div>
                            </template>
                        </a-table-column>
                        <a-table-column title="登录时间" data-index="create_time" align="center" :width="160">
                            <template #cell="{ record }">
                                <div class="text-grey">{{ record.create_time }}</div>
                            </template>
                        </a-table-column>
                        <a-table-column title="提示" data-index="content" :width="100">
                            <template #cell="{ record }">
                                <a-tooltip :content="record.content" v-if="record.content">
                                    <text-overflow :width="100" :text="record.content"> </text-overflow>
                                </a-tooltip>
                                <div v-else class="text-grey">--</div>
                            </template>
                        </a-table-column>
                    </template>
                </a-table>

                <div class="mt12 flex end">
                    <page :listPage="listPage" @change="pageChange"></page>
                </div>
            </div>
            <template #footer>
                <a-space>
                    <a-button @click="close()">关闭</a-button>
                </a-space>
            </template>
        </a-drawer>
    </div>
</template>

<script lang="ts">
export default {
    name: "loginLogs",
};
</script>
<script lang="ts" setup>
import { PageLimitType, Result, ResultError } from "@/types";
import { ref, getCurrentInstance, nextTick, reactive, onMounted } from "vue";
import { getListSystemLoginLogsByIdApi } from "@/api/system/login-logs";
import { useSetting } from "@/hooks/useSetting";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";
const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const visible = ref<boolean>(false);

const initLoading = ref<boolean>(false);

const lists = ref<any[]>([])

const toInit = (isInit: boolean = false) => {
    if (isInit) {
        listPage.value.page = 1;
    }
    let obj: any = {};
    obj.page = listPage.value.page;
    obj.limit = listPage.value.limit;
    initLoading.value = true;
    getListSystemLoginLogsByIdApi(operationId.value, obj)
        .then((res: Result) => {
            initLoading.value = false;
            lists.value = res.data.data;
            listPage.value.total = res.data.total;

            setTimeout(() => {
                initLoading.value = false;
            }, 300);
        })
        .catch((error: ResultError) => {
            initLoading.value = false;
            $utils.errorMsg(error);
        });
};



const listPage = ref<any>({
    total: 0,
    limit: useSetting().PageLimit.value,
    page: 1,
});

const pageChange = (res: PageLimitType) => {
    listPage.value = res;
    toInit();
};

const operationId = ref<number>(0);


const open = (id = 0) => {
    operationId.value = id;
    nextTick(() => {
        toInit(true);
    });
    visible.value = true;
};

const close = () => {
    proxy?.$refs["createRef"]?.resetFields();
    visible.value = false;
    return true;
};

defineExpose({ open });
</script>