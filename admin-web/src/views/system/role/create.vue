<template>
    <div>

        <a-modal :title="operation == 'create' ? '添加角色' : '编辑角色'" @BeforeOk="onCreateOk" @BeforeCancel="close"  :width="isMobile?'calc(100% - 20px)':'400px'"
            :top="useSetting().ModalTop" class="modal" v-model:visible="visible" :align-center="false" title-align="start"
            render-to-body>
            <a-form layout="vertical" :model="createForm" ref="createRef" :rules="createRules" v-loading="initLoading">
                <a-form-item label="角色名称：" field="role_name">
                    <a-input v-model="createForm.role_name" placeholder="请输入角色名称"></a-input>
                </a-form-item>
                <a-form-item label="备注：" field="remarks">
                    <a-input v-model="createForm.remarks" placeholder="备注"></a-input>
                </a-form-item>
            </a-form>
            <template #footer>
                <a-space>
                    <a-button v-btn @click="close">取消</a-button>
                    <a-button v-btn type="primary" :disabled="initLoading || btnLoading" :loading="btnLoading" @click="onCreateOk">确定</a-button>
                </a-space>
            </template>
        </a-modal>
    </div>
</template>

<script lang="ts">
export default {
    name: 'systemRoleCreate'
}
</script>

<script lang="ts" setup>
import { ref, getCurrentInstance, nextTick, reactive } from "vue";
import { createRoleApi, updateRoleApi, getDetailRoleApi } from "@/api/system/role";
import type { Result, ResultError } from "@/types";
import { useSetting } from "@/hooks/useSetting";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";
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
    role_name: string;
    remarks: string;
}>({
    role_name: "",
    remarks: "",
});

const createRules:{
    role_name: Array<{ required: boolean; message: string }>;
} = reactive({
    role_name: [{ required: true, message: "角色名称不能为空！" }],
});

const initLoading = ref<boolean>(false)

const toInit = () => {
    if (!operationId.value) {
        return;
    }
    initLoading.value = true;
    getDetailRoleApi({ id: operationId.value })
        .then((res: Result) => {
            createForm.value.role_name = res.data.role_name;
            createForm.value.remarks = res.data.remarks;
            initLoading.value = false;
        })
        .catch((err: ResultError) => {
            initLoading.value = false;
            $utils.errorMsg(err);

        });
};

const btnLoading = ref<boolean>(false);

const onCreateOk = () => {
    proxy?.$refs['createRef']?.validate((error: undefined | Record<string, ValidatedError>) => {
        if (error === undefined) {
            if (btnLoading.value) {
                return;
            }
            btnLoading.value = true;
            let operationApi: Promise<Result> | null = null;
            if (operation.value == "create") {
                operationApi = createRoleApi(createForm.value);
            } else {
                operationApi = updateRoleApi(
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

const open = (id = 0) => {
    if (id != 0) {
        operation.value = "update";
        operationId.value = id;
    } else {
        operation.value = "create";
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
    return true
};

defineExpose({ open });
</script>
