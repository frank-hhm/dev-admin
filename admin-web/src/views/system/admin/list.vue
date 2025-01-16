<template>
    <layout-body-content pageHeader header>
        <template #header>
            <a-card class="card-form">
                <a-form :layout="isMobile ? 'vertical' : 'horizontal'" :model="searchForm" ref="searchFormRef">
                    <a-row :gutter="20">
                        <a-col :md="8" :xs="24" :xl="6">
                            <a-form-item :label-col-flex="labelColFlex" label="账号" prop="account_like">
                                <a-input class="form-input-inline" v-model="searchForm.account_like"
                                    placeholder="请输入账号手机号" allow-clear />
                            </a-form-item>
                        </a-col>
                        <a-col :md="8" :xs="24" :xl="8">
                            <a-form-item :label-col-flex="labelColFlex" label="注册时间" prop="create_time">
                                <shortcuts-time v-model="searchForm.time" :btnShortcuts="false" />
                            </a-form-item>
                        </a-col>
                        <a-col :md="12" :xs="24" :xl="8">
                            <a-form-item :label-col-flex="labelColFlex" hide-label>
                                <a-space>
                                    <a-button @click="toInit()">查询</a-button>
                                    <a-button plain @click="onSearchReset()">重置</a-button>
                                </a-space>
                            </a-form-item>
                        </a-col>
                    </a-row>
                </a-form>
            </a-card>
            <div class="mt12"></div>
        </template>
        <template v-slot:page-header-left>
            账号列表
        </template>
        <template v-slot:page-header-right>
            <a-space>
                <a-tooltip content="刷新">
                    <a-button @click="toInit(true)" size="small"><icon-refresh /></a-button>
                </a-tooltip>
                <a-button type="primary" size="small" @click="onCreate(0)"
                    v-permission="'system-admin-create'">添加账号</a-button>
            </a-space>
            <systemAdminCreate ref="createComponentRef" @success="toInit(true)"></systemAdminCreate>
        </template>
        <template v-slot:content="{
                    height
                }">
            <a-table :loading="initLoading" :data="lists" row-key="id" isLeaf :pagination="false" :scroll="{
                    x: '100%',
                    y: height - 39
                }" :table-layout-fixed="true">
                <template #empty></template>
                <template #columns>
                    <a-table-column title="级别" data-index="level" align="left" :width="120">
                        <template #cell="{ record }">
                            <a-tag v-if="record.level == -1">
                                <template #icon>
                                    <icon-face-smile-fill />
                                </template>开发者</a-tag>
                            <a-tag v-else-if="record.level == 0"> <template #icon>
                                    <icon-user-group />
                                </template>超级管理员</a-tag>
                            <a-tag v-else> <template #icon>
                                    <icon-user />
                                </template>管理员</a-tag>
                        </template>
                    </a-table-column>
                    <a-table-column title="头像" data-index="avatar" align="center" :width="80">
                        <template #cell="{ record }">
                            <a-image :src="record.avatar" :height="32" :width="32" />
                        </template>
                    </a-table-column>
                    <a-table-column title="账号" data-index="account" :width="120">
                        <template #cell="{ record }">
                            <div class="admin-box">
                                <div>{{ record.account }}</div>
                                <div class="text-grey">{{ record.real_name }}</div>
                            </div>
                        </template>
                    </a-table-column>
                    <a-table-column title="邮箱" data-index="email" :width="120">
                        <template #cell="{ record }">
                            <div>{{ record.email }}</div>
                        </template>
                    </a-table-column>
                    <a-table-column title="登录次数" data-index="login_count" align="center" :width="100">
                        <template #cell="{ record }">
                            <div>{{ record.login_count }}</div>
                        </template>
                    </a-table-column>
                    <a-table-column title="最后一次登录时间" data-index="last_time" align="center" :width="160">
                        <template #cell="{ record }">
                            <div class="text-grey">{{ record.last_time }}</div>
                        </template>
                    </a-table-column>
                    <a-table-column title="最后一次登录IP" data-index="last_ip" :width="160">
                        <template #cell="{ record }">
                            <div>{{ record.last_ip?.value }}</div>
                            <div class="text-grey">{{ record.last_ip?.text }}</div>
                        </template>
                    </a-table-column>
                    <a-table-column title="状态" :fixed="isMobile ? undefined : 'right'" data-index="status"
                        align="center" :width="80">
                        <template #cell="{ record }">
                            <a-switch v-permission-disabled="'system-admin-status'" v-model="record.status.value"
                                :disabled="record.level < 1" size="small" type="round" :loading="record.loading"
                                :beforeChange="() => {
                    return (record.switch = true);
                }" @change=" onStatusChange($event, record)" :checked-value="1" :unchecked-value="0" />

                        </template>
                    </a-table-column>
                    <a-table-column title="操作" :fixed="isMobile ? undefined : 'right'" align="center" :width="140">
                        <template #cell="{ record }">
                            <a-space>
                                <a-button v-if="Number(adminInfo.level) < 1 || adminInfo.id == record.id"
                                    @click=" onCreate(record.id)" v-permission="'system-admin-update'"
                                    size="small">编辑</a-button>
                                <div v-if="record.level > 0" v-permission="'system-admin-delete'">
                                    <a-popconfirm content="确定删除吗？" @ok="onDelete(record.id)">
                                        <template #icon>
                                            <icon-exclamation-circle-fill type="red" />
                                        </template>
                                        <a-button size="small">删除</a-button>
                                    </a-popconfirm>
                                </div>
                            </a-space>
                        </template>
                    </a-table-column>
                </template>
            </a-table>
        </template>
        <template #footer>
            <page :listPage="listPage" @change="pageChange"></page>
        </template>
    </layout-body-content>
</template>

<script lang="ts" setup>
import {
    onMounted,
    ref,
    getCurrentInstance,
} from "vue";
import { getListAdminApi, updateStatusAdminApi, deleteAdminApi } from "@/api/system/admin";
import systemAdminCreate from "./create.vue";
import { PageLimitType, Result, ResultError } from "@/types";
import { useAdminStore, useAppStore } from "@/store";
import { useSetting } from "@/hooks/useSetting";
import { storeToRefs } from "pinia";

const labelColFlex = ref<string>("50px");

const { adminInfo } = storeToRefs(useAdminStore());
const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const searchFormRef = ref<HTMLElement>();

const searchForm = ref<{
    account_like: string;
    time: string[];
}>({
    account_like: "",
    time: [],
});

const onSearchReset = () => {
    proxy?.$refs["searchFormRef"]?.resetFields()
    searchForm.value.time = []
    toInit(true);
}

const initLoading = ref<boolean>(true);

const createComponentRef = ref<HTMLElement>();

const lists = ref<any>([]);

const toInit = (isInit: boolean = false) => {
    if (isInit) {
        listPage.value.page = 1;
    }
    let obj: any = {};
    obj.page = listPage.value.page;
    obj.limit = listPage.value.limit;
    obj.account_like = searchForm.value.account_like;
    obj.time = searchForm.value.time;
    initLoading.value = true;
    getListAdminApi(obj)
        .then((res: Result) => {
            initLoading.value = false;
            lists.value = res.data.data;
            listPage.value.total = res.data.total;

            setTimeout(() => {
                initLoading.value = false;
            }, 300);
        })
        .catch((error: ResultError) => {
            $utils.errorMsg(error);
        });
};

const onCreate = (id: number | string) => {
    proxy?.$refs["createComponentRef"]?.open(id, 1);
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

const onDelete = (id: number | string) => {
    deleteAdminApi({ id })
        .then((res: Result) => {
            toInit();
            $utils.successMsg(res.message);
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};

const onStatusChange = (val: any, row: any) => {
    if (row.switch === true) {
        row.loading = true;
        updateStatusAdminApi({
            id: row.id,
            status: val,
        })
            .then((res: Result) => {
                row.loading = false;
                toInit();
                $utils.successMsg(res);
            })
            .catch((err: ResultError) => {
                row.loading = false;
                toInit();
                $utils.errorMsg(err);
            });
    }
};


onMounted(() => {
    toInit();
});
</script>
<style scoped></style>