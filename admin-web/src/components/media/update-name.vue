<template>
    <a-modal title-align="start" v-model:visible="visible" title="编辑素材名称" @BeforeOk="onSave" @BeforeCancel="close"
        :width="isMobile ? 'calc(100% - 20px)' : '400px'" esc-to-close>
        <a-form class="mt10" layout="vertical" :model="createForm" ref="createRef" :rules="createRules"
            label-width="100px" size="large">
            <a-form-item field="former_name" hide-label hide-asterisk label="素材名称：">
                <a-input v-model="createForm.former_name"></a-input>
            </a-form-item>
        </a-form>
        <template #footer>
            <a-space>
                <a-button @click="visible = false">取消</a-button>
                <a-button type="primary" @click="onSave" :disabled="btnLoading" :loading="btnLoading">确定</a-button>
            </a-space>
        </template>
    </a-modal>
</template>
<script lang="ts">
export default {
    name: "mediaUpdateName",
};
</script>
<script lang="ts" setup>
import { ref, reactive, getCurrentInstance } from "vue";
import { Result, ResultError } from "@/types";
import { successMsg } from "@/utils";
import { updateMediaNameApi } from "@/api/media";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";
import { ValidatedError } from "@arco-design/web-vue";
const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const emit = defineEmits(["success"]);

const visible = ref<boolean>(false);

const createRef = ref<HTMLElement>();

const operation = ref<string>("update");

const operationId = ref<number | string>(0);

const btnLoading = ref<boolean>(false)

const createForm = ref({
    former_name: "",
});
const createRules = reactive({
    former_name: [{ required: true, message: "素材名称不能为空！" }],
});

const onSave = () => {
    proxy?.$refs['createRef'].validate((error: undefined | Record<string, ValidatedError>) => {
        if (error === undefined) {
            if (btnLoading.value) {
                return;
            }
            btnLoading.value = true;
            updateMediaNameApi({
                id: operationId.value,
                former_name: createForm.value.former_name,
            })
                .then((res: Result) => {
                    $utils.successMsg("保存成功");
                    close();
                    emit("success");
                    btnLoading.value = false;
                })
                .catch((err: ResultError) => {
                    $utils.errorMsg(err);
                    btnLoading.value = false;
                });
        }
    });
};
const open = (id: number = 0, name: string = "") => {
    operation.value = "update";
    operationId.value = id;
    createForm.value.former_name = name;
    visible.value = true;
};

const close = () => {
    proxy?.$refs['createRef'].resetFields();
    operationId.value = 0;
    visible.value = false;
    return true
};

defineExpose({ open });
</script>