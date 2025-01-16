<template>
    <div>
        <a-modal title-align="start" v-model:visible="visible" :title="operation == 'create' ? '添加分组' : '编辑分组'"
            @BeforeOk="onSave" @BeforeCancel="close" :width="isMobile ? 'calc(100% - 20px)' : '400px'" esc-to-close
            render-to-body>
            <a-form :model="createForm" :layout="isMobile ? 'vertical' : 'horizontal'" ref="createRef"
                :rules="createRules">
                <a-form-item field="pid" hide-label hide-asterisk class="group-form-item ">
                    <a-cascader v-model="createForm.pid" :options="cascader" :loading="cascaderLoading"
                        @change="pidChange" allow-search allow-clear class="form-select-fil" check-strictly
                        placeholder="请选择父级" />
                </a-form-item>
                <a-form-item field="group_name" hide-label hide-asterisk class="group-form-item ">
                    <a-input v-model="createForm.group_name" placeholder="请输入分组名称"></a-input>
                </a-form-item>
            </a-form>
            <template #footer>
                <a-space>
                    <a-button @click="visible = false">取消</a-button>
                    <a-button type="primary" :loading="btnLoading" :disabled="btnLoading"
                        @click="onSave()">确定</a-button>
                </a-space>
            </template>
        </a-modal>
    </div>
</template>
<script lang="ts">
export default {
    name: "mediaGroupCreate",
};
</script>
<script lang="ts" setup>
import { ref, reactive, getCurrentInstance, nextTick } from "vue";
import { Result, ResultError } from "@/types";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";

import {
    createMediaGroupApi,
    updateMediaGroupApi,
    getCascaderApi
} from "@/api/media";
import { ValidatedError } from "@arco-design/web-vue";

const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const emit = defineEmits(["success"]);

const operation = ref<string>("create");

const operationId = ref<number>(0);

const visible = ref<boolean>(false);

const createRef = ref<HTMLElement>();

const createForm = ref<{
    group_name: string,
    type: string;
    pid: any;
}>({
    group_name: "",
    type: "image",
    pid: null
});
const createRules = reactive({
    group_name: [
        {
            required: true,
            message: "分组名称不能为空",
            trigger: "blur",
        },
    ],
});

const open = (type: string, id: number = 0, group_name: string = '', pid: any = null) => {
    createForm.value.type = type
    if (id != 0) {
        operation.value = "update";
        operationId.value = id;
        createForm.value.pid = pid == 0 ? null : pid,
            createForm.value.group_name = group_name
    } else {
        operation.value = "create";
    }
    nextTick(() => {
        initCascader();
        toInit();
    });
    visible.value = true;
};

const toInit = () => {
    if (!operationId.value) {
        return;
    }
};

const btnLoading = ref<boolean>(false)

const onSave = () => {
    proxy?.$refs['createRef']?.validate((error: undefined | Record<string, ValidatedError>) => {
        if (error === undefined) {
            if (btnLoading.value) {
                return
            }
            btnLoading.value = true
            let operationApi: any = null;
            if (operation.value == "create") {
                operationApi = createMediaGroupApi(createForm.value);
            } else {
                operationApi = updateMediaGroupApi(
                    Object.assign(
                        {
                            id: operationId.value,
                        },
                        createForm.value
                    )
                );
            }
            operationApi
                .then((res: Result) => {
                    $utils.successMsg(res.message);
                    close();
                    emit("success");
                    btnLoading.value = false
                })
                .catch((err: ResultError) => {
                    $utils.errorMsg(err);
                    btnLoading.value = false
                });
        }
    });
};

const createLoading = ref<boolean>(false);

const pidChange = () => {

}

const cascader = ref<any>([]);

const cascaderLoading = ref<boolean>(true);

const initCascader = () => {
    let obj: any = {};
    obj["type"] = createForm.value.type;
    if (operationId.value) {
        obj["pid"] = operationId.value;
    }
    cascaderLoading.value = true
    getCascaderApi(obj)
        .then((res: Result) => {
            cascader.value = res.data.list;
            cascaderLoading.value = false
        })
        .catch((err: ResultError) => {
            cascaderLoading.value = false
            $utils.errorMsg(err);
        });
};

const close = () => {
    let res = proxy?.$refs['createRef']?.resetFields();
    console.log(res, proxy?.$refs['createRef'])
    operationId.value = 0;
    visible.value = false;
    return true;
};

defineExpose({ open });
</script>
<style scoped></style>