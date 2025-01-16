<template>
    <div>
        <a-modal :title="operation == 'create' ? '添加菜单' : '编辑菜单'" @BeforeOk="onCreateOk" @BeforeCancel="close" :width="isMobile ? 'calc(100% - 20px)' : '900px'"
            :top="useSetting().ModalTop" class="modal" v-model:visible="visible" :align-center="false" title-align="start"
            render-to-body>
            <a-form :model="createForm" :layout="isMobile?'vertical':'horizontal'" ref="createRef" :rules="createRules" v-loading="initLoading">

                <a-row :gutter="20">
                    <a-col :md="24" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" label="菜单名称" field="menu_name">
                            <a-input v-model="createForm.menu_name" placeholder="请输入菜单名称"></a-input>
                        </a-form-item>
                    </a-col>
                    <a-col :md="24" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" label="模块" field="module">
                            <a-radio-group v-model="createForm.module">
                                <a-radio :value="1">系统</a-radio>
                            </a-radio-group>
                        </a-form-item>
                    </a-col>
                    <a-col :md="24" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" label="类型" field="type">
                            <a-radio-group v-model="createForm.type">
                                <a-radio :value="1">菜单</a-radio>
                                <a-radio :value="2">事件</a-radio>
                            </a-radio-group>
                        </a-form-item>
                    </a-col>
                    <a-col :md="12" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" label="父级分类" field="pid">
                            <a-cascader v-model="createForm.pid" :options="menusCascader" allow-search
                                allow-clear class="form-select-fil" check-strictly placeholder="请选择父级分类" />
                        </a-form-item>
                    </a-col>

                    <a-col :md="12" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" :label="'图标'" field="icon" class="default-append">
                            <a-input v-model="createForm.icon" placeholder="选择图标">
                                <template #prefix>
                                    <icon-user />
                                </template>
                                <template #append>
                                    <a-button @click="() => {
                                        proxy?.$refs['selectModalIconRef']?.open();
                                    }
                                        ">
                                        图标
                                    </a-button>
                                    <select-icon-modal ref="selectModalIconRef" @change="iconChange"></select-icon-modal>
                                </template>
                            </a-input>
                        </a-form-item>
                    </a-col>
                </a-row>

                <a-row :gutter="20">
                    <a-col :md="12" :xs="24" v-if="createForm.type != 3">
                        <a-form-item :label-col-flex="labelColFlex" label="路由地址" field="menu_path">
                            <a-input v-model="createForm.menu_path" placeholder="请输入路由地址">
                            </a-input>
                        </a-form-item>
                    </a-col>

                    <a-col :md="12" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" label="接口" field="api_rule" class="default-append">
                            <a-input disabled v-model="createForm.api_rule" placeholder="请选择接口">
                                <template #append>
                                    <a-button @click="() => {
                                        proxy?.$refs['selectRuleRef']?.open();
                                    }">
                                        接口
                                    </a-button>
                                </template>
                            </a-input>
                        </a-form-item>
                    </a-col>
                    <a-col :md="12" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" label="权限标识" field="menu_node">
                            <a-input v-model="createForm.menu_node" placeholder="请输入权限标识">
                            </a-input>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="20">
                    <a-col :md="24" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" label="参数" field="params">
                            <a-input v-model="createForm.params"></a-input>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="20">
                    <a-col :md="12" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" label="排序：" field="sort">
                            <a-input-number v-model="createForm.sort" :min="0" :max="10000" />
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="20">
                    <a-col :md="12" :xs="24">
                        <a-form-item :label-col-flex="labelColFlex" label="状态" field="status">
                            <a-radio-group v-model="createForm.status">
                                <a-radio v-for="item in StatusEnum" :value="item.value" :key="item.value">{{ item.name
                                }}</a-radio>
                            </a-radio-group>
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-form>
            <template #footer>
                <a-space>
                    <a-button v-btn @click="close">取消</a-button>
                    <a-button v-btn type="primary" :disabled="initLoading || btnLoading" :loading="initLoading" @click="onCreateOk">确定</a-button>
                </a-space>
            </template>
            <select-rule-modal ref="selectRuleRef" @change="ruleChange"></select-rule-modal>
        </a-modal>
    </div>
</template>


<script lang="ts" >
export default {
    name: "menusCreateComponent",
};
</script>
<script lang="ts" setup>
import { ref, reactive, getCurrentInstance, nextTick } from "vue";
import { useEnumStore,useAppStore } from "@/store";
import { getCascaderMenusApi, createMenusApi, getDetailMenusApi, updateMenusApi } from "@/api/system/menus";
import type { EnumItemType, Result, ResultError } from "@/types";
import { useSetting } from "@/hooks/useSetting";

import { storeToRefs } from "pinia";
import { FieldRule, ValidatedError } from "@arco-design/web-vue";

const { isMobile } = storeToRefs(useAppStore());
const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const labelColFlex = ref<string>("60px");

const StatusEnum = ref<EnumItemType[]>(useEnumStore().getEnumItem("StatusEnum"));

const operation = ref<string>("create");

const operationId = ref<number>(0);

const visible = ref<boolean>(false);

const createRef = ref<HTMLElement>();

interface listItem {
    module: number | string
    type: number
    menu_name: string
    pid: string | number| any,
    menu_path:string
    menu_node:string
    params:string
    icon:string
    sort:number
    status:number
    api_rule:string
}

const createForm = ref<listItem>({
    module: 1,
    type: 1,
    menu_name: "",
    pid: null,
    menu_path: "",
    menu_node: "",
    params: "",
    icon: "",
    sort: 0,
    status: 1,
    api_rule: "",
});

const toInit = () => {
    initCascader();
    if (!operationId.value) {
        return;
    }
    initLoading.value = true;
    getDetailMenusApi({ id: operationId.value })
        .then((res: Result) => {
            createForm.value.type = Number(res.data.type);
            createForm.value.module = Number(res.data.module);
            createForm.value.menu_name = res.data.menu_name;
            if(res.data.pid > 0){
                createForm.value.pid = Number(res.data.pid);
            }
            createForm.value.menu_path = res.data.menu_path;
            createForm.value.menu_node = res.data.menu_node;
            createForm.value.params = res.data.params;
            createForm.value.icon = res.data.icon;
            createForm.value.sort = res.data.sort;
            createForm.value.status = res.data.status.value;
            createForm.value.api_rule = res.data.api_rule;

            initLoading.value = false;
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};

const createRules: Record<string, FieldRule | FieldRule[]>
 = reactive({
    menu_name: [{ required: true, message: "菜单名称不能为空！" }],
});

const initLoading = ref<boolean>(false);

const emit = defineEmits(["success"]);

const btnLoading = ref(false);

const onCreateOk = () => {
    proxy?.$refs['createRef']?.validate((error: undefined | Record<string, ValidatedError>) => {
        if (error === undefined) {
            if (btnLoading.value) {
                return;
            }
            btnLoading.value = true;
            let operationApi: Promise<Result> | null = null;
            if (operation.value == "create") {
                operationApi = createMenusApi(createForm.value);
            } else {
                operationApi = updateMenusApi(
                    Object.assign(
                        {
                            id: operationId.value,
                        },
                        createForm.value
                    )
                );
            }
            if(operationApi){
                operationApi
                .then((res: Result) => {
                    $utils.successMsg(res.message);
                    close();
                    emit("success");
                    btnLoading.value = false;
                })
                .catch((err: ResultError) => {
                    $utils.errorMsg(err);
                    btnLoading.value = false;
                });
            }
        }
    });
};

interface menusCascaderType {
    pid:number;
    label:string;
    disabled:boolean;
    value:number;
    children:menusCascaderType[]
}

const menusCascader = ref<menusCascaderType[]>([]);

const initCascader = () => {
    let obj: {
        pid?:number
    } = {};
    if (operationId.value) {
        obj["pid"] = operationId.value;
    }
    getCascaderMenusApi(obj)
        .then((res: Result) => {
            menusCascader.value = res.data.menuList;
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};

const selectRuleRef = ref<HTMLElement>();

const selectModalIconRef = ref<HTMLElement>();

const ruleChange = (ruleItem: {
    rule: string;
}) => {
    // createForm.value.menu_name = ruleItem.title;
    createForm.value.api_rule = ruleItem.rule;
};
const iconChange = (icon: string) => {
    createForm.value.icon = icon;
};

const open = (id: number = 0, _module: string | number) => {
    if (id != 0) {
        operation.value = "update";
        operationId.value = id;
    } else {
        operation.value = "create";
    }
    if (_module != 'all') {
        createForm.value.module = _module
    }
    nextTick(() => {
        toInit();
    });
    visible.value = true;
};

const close = () => {
    proxy?.$refs['createRef']?.resetFields();
    operationId.value = 0;
    visible.value = false;
    return true;
};

defineExpose({ open });
</script>
<style scoped></style>