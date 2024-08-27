<template>
    <div>
        <a-button v-permission="'system-menus-create'" type="primary" @click="onCreate(0)">
            添加菜单
        </a-button>
        <!-- 添加 -->
        <menusCreateComponent ref="createComponentRef" @success=" toInit()"></menusCreateComponent>
    </div>
    <div class="mt12"></div>
    <layout-body-tabs :tabs="levelTabs" v-model="menusLevel" v-model:loading="initLoading" @change="changeType">
        <div class="m12">
            <!-- 列表 -->
            <a-table :loading="initLoading" :data="lists" row-key="id" isLeaf :pagination="false" :bordered="false">
                <template #columns>
                    <a-table-column title="菜单名称" data-index="menu_name" :width="400">
                        <template #cell="{ record }">
                            <span class="text-grey">{{ record.html }}</span>
                            <span>{{ record.menu_name }}</span>
                        </template>
                    </a-table-column>

                    <a-table-column title="图标" data-index="icon" :width="80">
                        <template #cell="{ record }">
                            <span class="icon" :class="record.icon"></span>
                        </template>
                    </a-table-column>
                    <a-table-column title="路由地址" data-index="menu_path" :width="160">
                        <template #cell="{ record }">
                            <span class="text-grey">{{ record.menu_path }}</span>
                        </template>
                    </a-table-column>
                    <a-table-column title="Api接口" data-index="api_rule" :width="160">
                        <template #cell="{ record }">
                            <span class="text-grey">{{ record.api_rule }}</span>
                        </template>
                    </a-table-column>
                    <a-table-column title="权限标识" data-index="menu_node" :width="160">
                        <template #cell="{ record }">
                            <span class="text-grey">{{ record.menu_node }}</span>
                        </template>
                    </a-table-column>
                    <a-table-column title="状态" fixed="right" data-index="status" align="center" :width="80">
                        <template #cell="{ record }">
                            <a-switch v-model="record.status.value" size="small" type="round" :loading="record.loading"
                                :beforeChange="() => {
            return (record.switch = true);
        }" @change=" onStatusChange($event, record)" :checked-value="1" :unchecked-value="0" />

                        </template>
                    </a-table-column>
                    <a-table-column title="操作" fixed="right" align="center" :width="140">
                        <template #cell="{ record }">
                            <a-space>
                                <a-button :hoverable="false" @click="onCreate(record.id)"
                                    v-permission="'system-menus-update'" size="small">编辑</a-button>

                                <div v-permission="'system-menus-delete'">
                                    <a-popconfirm content="确定删除吗？" @ok=" onDelete(record.id)">
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
        </div>
    </layout-body-tabs>
</template>
<script lang="ts" setup>
import { ref, getCurrentInstance, onMounted, nextTick } from "vue";

import { getListMenusApi, updateStatusMenusApi, deleteMenusApi } from "@/api/system/menus";
import menusCreateComponent from "./create.vue";
import { useEnumStore, useMenusStore } from "@/store";
import { EnumType, Result, ResultError } from "@/types";

const MenusStore = useMenusStore();

const { proxy } = getCurrentInstance() as any;
const {
    proxy: { $utils },
} = getCurrentInstance() as any;

const createComponentRef = ref<HTMLElement>();

const initLoading = ref<boolean>(true);

const lists = ref<any>([]);

const toInit = (initMenu: boolean = false) => {
    initLoading.value = true;
    let obj: any = {};
    if (menusLevel.value != "") {
        obj.module = menusLevel.value;
    }
    getListMenusApi(obj)
        .then((res: Result) => {
            lists.value = res.data.list;
            if (!initMenu) {
                MenusStore.setMenus(res.data.menusList);
            }
            setTimeout(() => {
                initLoading.value = false;
            }, 300);
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
            initLoading.value = false;
        });
};

const levelTabs = ref<EnumType>([
    {
        name: "全部",
        value: "all",
    },
    {
        name: "系统",
        value: "1",
    },
]);

onMounted(() => {
    nextTick(() => {
        let AppsTypeEnum = useEnumStore().getEnumItem("AppsTypeEnum");
        for (let k in AppsTypeEnum) {
            levelTabs.value.push(AppsTypeEnum[k]);
        }
    });
    toInit(true);
});

const menusLevel = ref<string | number>("all");

const changeType = (val: string | number) => {
    menusLevel.value = val;
    toInit();
};

const onCreate = (id: number | string) => {
    proxy?.$refs["createComponentRef"]?.open(id, menusLevel.value);
};

const onStatusChange = (val: any, row: any) => {
    if (row.switch === true) {
        row.loading = true;
        initLoading.value = true;
        updateStatusMenusApi({
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

const onDelete = (id: number) => {
    deleteMenusApi({ id })
        .then((res: Result) => {
            toInit();
            $utils.successMsg(res.message);
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};
</script>
<style scoped></style>