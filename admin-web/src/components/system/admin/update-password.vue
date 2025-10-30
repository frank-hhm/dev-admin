<template>
    <a-modal v-model:visible="visible" title="修改密码" @BeforeOk="onSave" @BeforeCancel="close" :width="isMobile?'calc(100% - 20px)':'400px'"
        :top="useSetting().ModalTop" :align-center="false" title-align="start">
        <div v-loading="initLoading">
            <a-form layout="vertical" :model="createForm" ref="createRef" :rules="createRules">
                <a-form-item :label-col-flex="labelColFlex" label="原密码" field="old_pwd">
                    <a-input-password v-model="createForm.old_pwd" placeholder="请输入原密码" allow-clear />
                </a-form-item>
                <a-form-item :label-col-flex="labelColFlex" label="新密码" field="pwd">
                    <a-input-password v-model="createForm.pwd" placeholder="请输入新密码" allow-clear />
                </a-form-item>
                <a-form-item :label-col-flex="labelColFlex" label="确定密码" field="conf_pwd">
                    <a-input-password v-model="createForm.conf_pwd" placeholder="请再次输入密码" allow-clear/>
                </a-form-item>
            </a-form>
        </div>
        <template #footer>
            <a-space>
                <a-button @click="close()">取消</a-button>
                <a-button type="primary" @click="onSave()" :loading="btnLoading" :disabled="btnLoading">确定</a-button>
            </a-space>
        </template>
    </a-modal>
</template>
          
<script lang="ts">
export default {
    name: "account-admin-pass",
};
</script>
<script lang="ts" setup>
import { ref, getCurrentInstance, reactive, onMounted } from "vue";
import { updatePassAdminApi } from "@/api/system/admin";
import type { Result, ResultError } from "@/types";
import { useSetting } from "@/hooks/useSetting";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";
import { ValidatedError } from "@arco-design/web-vue";

const { isMobile } = storeToRefs(useAppStore());

const labelColFlex = ref<string>("80px");

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const emit = defineEmits(["success"]);

const visible = ref<boolean>(false);

const createRef = ref<HTMLElement>();

const createForm = ref({
    old_pwd: "",
    pwd: "",
    conf_pwd: "",
});

const createRules: any = reactive({
    old_pwd: [{ required: true, message: "原密码不能为空！", trigger: "blur" }],
    pwd: [{ required: true, message: "请输入密码", trigger: "blur" }],
    conf_pwd: [{ required: true, message: "请再次密码", trigger: "blur" }],
});

const initLoading = ref<boolean>(false);

const btnLoading = ref<boolean>(false);

const onSave = () => {
    proxy?.$refs['createRef'].validate((error: undefined | Record<string, ValidatedError>) => {
        if (error === undefined) {
            if (btnLoading.value) {
                return;
            }
            btnLoading.value = true;
            updatePassAdminApi(createForm.value)
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
    });
};

const open = () => {
    visible.value = true;
};

const close = () => {
    proxy?.$refs['createRef']?.resetFields();
    visible.value = false;
    createRules.pwd = [];
    createRules.conf_pwd = [];
    return true;
};

defineExpose({ open });
</script>