<template>
    <div>
        <a-modal title-align="start" v-model:visible="visible" :title="operation == 'create' ? '添加分组' : '编辑分组'"
            @BeforeOk="onSave" @BeforeCancel="close" width="400px" esc-to-close unmount-on-close>
            <a-form :model="createForm" layout="vertical" ref="createRef" :rules="createRules">
                <a-form-item field="group_name" hide-label hide-asterisk class="group-form-item ">
                    <a-input v-model="createForm.group_name" placeholder="请输入分组名称"></a-input>
                </a-form-item>
            </a-form>
            <template #footer>
                <a-space>
                    <a-button @click="visible = false">取消</a-button>
                    <a-button type="primary" :loading="btnLoading" :disabled="btnLoading" @click="onSave()">确定</a-button>
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

import {
    createMediaGroupApi,
    updateMediaGroupApi,
} from "@/api/media";

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
    type: string
}>({
    group_name: "",
    type: "image"
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

const open = (type: string, id: number = 0, group_name: string = '') => {
    createForm.value.type = type
    if (id != 0) {
        operation.value = "update";
        operationId.value = id;
        createForm.value.group_name = group_name
    } else {
        operation.value = "create";
    }
    nextTick(() => {
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
    proxy?.$refs['createRef']?.validate((valid: any, fields: any) => {
        if (!valid) {
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

const close = () => {
    console.log(proxy?.$refs['createRef'])
    proxy?.$refs['createRef']?.resetFields();
    operationId.value = 0;
    visible.value = false;
    return true;
};

defineExpose({ open });
</script>
<style scoped>
.group-form-item {
    margin-bottom: 0 !important;
}
</style>