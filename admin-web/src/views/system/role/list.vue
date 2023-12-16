<template>
    <layout-body>
        <div>
            <a-button type="primary" @click="onCreate(0)" v-permission="'system-role-create'">
                添加角色
            </a-button>
        </div>
        <systemRoleCreate ref="createComponentRef" @success=" toInit(true)"></systemRoleCreate>
        <systemRoleAuth ref="systemRoleAuthRef" @success=" toInit(true)"></systemRoleAuth>

        <!-- 列表 -->
        <a-table :loading="initLoading" class="mt20" :data="lists" row-key="id" isLeaf :pagination="false">
            <template #columns>
                <a-table-column title="角色名称" data-index="role_name">
                    <template #cell="{ record }">
                        <span>{{ record.role_name }}</span>
                    </template>
                </a-table-column>
                <a-table-column title="备注" data-index="remarks">
                    <template #cell="{ record }">
                        <span class="text-grey">{{ record.remarks }}</span>
                    </template>
                </a-table-column>

                <a-table-column title="创建时间" data-index="create_time" :width="160">
                    <template #cell="{ record }">
                        <span class="text-grey">{{ record.create_time }}</span>
                    </template>
                </a-table-column>
                <a-table-column title="操作" align="center" :width="140">
                    <template #cell="{ record }">
                        <a-space>
                            <a-button v-permission="'system-role-auth'" @click=" goAuthRole(record.id)"
                                size="small">授权设置</a-button>
                            <a-button @click=" onCreate(record.id)" v-permission="'system-role-update'"
                                size="small">编辑</a-button>
                            <div v-permission="'system-role-delete'">
                                <a-popconfirm content="确定删除吗？" @confirm=" onDelete(record.id)">
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
    </layout-body>
</template>

<script lang="ts" setup>
import { ref, onMounted, getCurrentInstance } from "vue";
import systemRoleCreate from "./create.vue";
import systemRoleAuth from "./auth.vue"
import { getListRoleApi, deleteRoleApi } from "@/api/system/role";
import { PageLimitType, Result, ResultError } from "@/types";
import { useSetting } from "@/hooks/useSetting";
import router from "@/router";

const { proxy } = getCurrentInstance() as any;
const {
    proxy: { $utils },
} = getCurrentInstance() as any;

const initLoading = ref<boolean>(true);

const lists = ref<any>([]);

const createComponentRef = ref<HTMLElement>();

const onCreate = (id: number | string) => {
    proxy?.$refs["createComponentRef"].open(id);
};

const listPage = ref<PageLimitType>({
    total: 0,
    limit: useSetting().PageLimit.value,
    page: 1,
});

const pageChange = (res: PageLimitType) => {
    listPage.value = res;
    toInit();
};

const toInit = (isInit: boolean = false) => {
    if (isInit) {
        listPage.value.page = 1;
    }
    initLoading.value = true;
    let obj: any = {};
    obj.page = listPage.value.page;
    obj.limit = listPage.value.limit;
    getListRoleApi(obj)
        .then((res: Result) => {
            lists.value = res.data.data;
            listPage.value.total = res.data.total;
            setTimeout(() => {
                initLoading.value = false;
            }, 300);
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};

const onDelete = (id: number) => {
    deleteRoleApi({
        id,
    })
        .then((res: Result) => {
            toInit();
            $utils.successMsg(res.message);
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};

const goAuthRole = (id: number) => {
    proxy?.$refs["systemRoleAuthRef"].open(id);
};

onMounted(() => {
    toInit();
});
</script>
  