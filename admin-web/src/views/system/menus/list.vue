<template>
    <layout-body-content pageHeader hideFooter>
        <template v-slot:page-header-left>
            <a-radio-group type="button" v-model="menusLevel" @change="changeType">
                <a-radio v-for="(item, index) in levelTabs" :key="index" :disabled="initLoading" :value="item.value">{{
                item.name }}</a-radio>
            </a-radio-group>
        </template>
        <template v-slot:page-header-right>
            <a-space>
                <a-tooltip content="刷新">
                    <a-button @click="toInit()" size="small"><icon-refresh /></a-button>
                </a-tooltip>
                <a-button v-permission="'system-menus-create'" type="primary" size="small" @click="onCreate(0)">
                    添加菜单
                </a-button>
            </a-space>
            <!-- 添加 -->
            <menusCreateComponent ref="createComponentRef" @success=" toInit()"></menusCreateComponent>
        </template>
        <template v-slot:content="{
                height
            }">
            <!-- 列表 -->
            <a-table :loading="initLoading" :data="lists" row-key="id" isLeaf :pagination="false" :bordered="false"
                :scroll="{
                x: '100%',
                y: height - 39
            }">
                <template #columns>
                    <a-table-column title="菜单名称" data-index="menu_name" :minWidth="240">
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
                    <a-table-column title="Api接口" data-index="api_rule" :minWidth="200">
                        <template #cell="{ record }">
                            <span class="text-grey">{{ record.api_rule }}</span>
                        </template>
                    </a-table-column>
                    <a-table-column title="权限标识" data-index="menu_node" :width="160">
                        <template #cell="{ record }">
                            <span class="text-grey">{{ record.menu_node }}</span>
                        </template>
                    </a-table-column>
                    <a-table-column title="状态" :fixed="isMobile ? undefined : 'right'" data-index="status"
                        align="center" :width="80">
                        <template #cell="{ record }">
                            <a-switch v-permission-disabled="'system-menus-status'" v-model="record.status.value"
                                size="small" type="round" :loading="record.loading" :beforeChange="() => {
                return (record.switch = true);
            }" @change=" onStatusChange($event, record)" :checked-value="1" :unchecked-value="0" />

                        </template>
                    </a-table-column>
                    <a-table-column title="操作" :fixed="isMobile ? undefined : 'right'" align="center" :width="140">
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
        </template>
    </layout-body-content>
</template>
<script lang="ts" setup>
import { ref, getCurrentInstance, onMounted, nextTick } from "vue";

import { getListMenusApi, updateStatusMenusApi, deleteMenusApi } from "@/api/system/menus";
import menusCreateComponent from "./create.vue";
import { useEnumStore, useMenusStore } from "@/store";
import { EnumType, Result, ResultError } from "@/types";

import { useAppStore } from "@/store";
import { storeToRefs } from "pinia";

const { isMobile } = storeToRefs(useAppStore());
const MenusStore = useMenusStore();

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const createComponentRef = ref<HTMLElement>();

const initLoading = ref<boolean>(true);

interface listItemType {
    id: number;
    menu_name: string;
    icon: string;
    menu_path: string;
    api_rule: string;
    sort: number;
    status: number;
    pid: number;
    module: number;
    type: number;
    children: listItemType[];
    params: string;
    menu_node: string;
    switch?: boolean;
    loading?: boolean;
}


const lists = ref<listItemType[]>([]);

const toInit = (initMenu: boolean = false) => {
    initLoading.value = true;
    let obj: {
        module?: number | string;
    } = {};
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
    toInit(false);
});

const menusLevel = ref<string | number>("all");

const changeType = (val: string | number | boolean) => {
    menusLevel.value = String(val);
    toInit();
};

const onCreate = (id: number | string) => {
    proxy?.$refs["createComponentRef"]?.open(id, menusLevel.value);
};

const onStatusChange = (val: boolean | string | number, row: listItemType) => {
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