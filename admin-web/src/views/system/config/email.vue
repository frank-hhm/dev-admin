<template>
    <a-card title="邮箱配置" class="card" v-loading="initLoading">
        <a-form :model="configForm" ref="configFormRef">
            <div class="card-form-box" style="max-width:400px;">
                <a-form :model="configForm" laba-width="160px" ref="configFormRef">
                    <a-form-item :label-col-flex="labelColFlex" label="服务器地址" field="mail_imap_host">
                        <a-input class="form-input-inline" v-model="configForm.mail_imap_host" placeholder="请输入服务器地址"
                            allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="端口" field="mail_imap_port">
                        <a-input-number class="form-input-inline" v-model="configForm.mail_imap_port"
                            placeholder="请输入服务器端口" allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="用户名" field="mail_username">
                        <a-input class="form-input-inline" v-model="configForm.mail_username" placeholder="请输入服务器的用户名"
                            allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="密码" field="mail_password">
                        <a-input class="form-input-inline" v-model="configForm.mail_password" placeholder="请输入服务器的密码"
                            allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="默认发送地址" field="mail_default_from">
                        <a-input class="form-input-inline" v-model="configForm.mail_default_from"
                            placeholder="请输入默认发送邮件地址" allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="默认发送名称" field="mail_default_from_name">
                        <a-input class="form-input-inline" v-model="configForm.mail_default_from_name"
                            placeholder="请输入默认发送名称" allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex">
                        <a-button type="primary" @click="onSave()" :loading="btnLoading"
                            :disabled="btnLoading">确定</a-button>
                    </a-form-item>
                </a-form>
            </div>
        </a-form>
    </a-card>
</template>
<script lang="ts" setup>
import { getConfigApi, saveConfigApi } from "@/api/system/config";
import { EnumType, Result, ResultError } from "@/types";
import { ValidatedError } from "@arco-design/web-vue";
import { onMounted, ref, getCurrentInstance } from "vue";

const labelColFlex = ref<string>("120px");

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const configFormRef = ref<HTMLElement>();

interface ConfigFormType {
    mail_imap_host: string;
    mail_imap_port: number | undefined;
    mail_username: string;
    mail_password: string;
    mail_default_from: string;
    mail_default_from_name: string;
}

const configForm = ref<ConfigFormType>({
    mail_imap_host: "",
    mail_imap_port: undefined,
    mail_username: "",
    mail_password: "",
    mail_default_from: "",
    mail_default_from_name: ""
})

const initLoading = ref<boolean>(false)

const toInit = () => {
    initLoading.value = true
    getConfigApi("email").then((res: Result) => {
        configForm.value.mail_imap_host = String(res.data.mail_imap_host || '')
        if (res.data.mail_imap_port) {
            configForm.value.mail_imap_port = Number(res.data.mail_imap_port)
        }
        configForm.value.mail_username = String(res.data.mail_username || '')
        configForm.value.mail_password = String(res.data.mail_password || '')
        configForm.value.mail_default_from = String(res.data.mail_default_from || '')
        configForm.value.mail_default_from_name = String(res.data.mail_default_from_name || '')
        initLoading.value = false;
    }, (err: ResultError) => {
        initLoading.value = false;
        $utils.errorMsg(err);
    })
}

const btnLoading = ref<boolean>(false)

const onSave = () => {
    proxy?.$refs['configFormRef']?.validate((error: undefined | Record<string, ValidatedError>) => {
        if (error === undefined) {
            if (btnLoading.value) {
                return;
            }
            btnLoading.value = true;
            saveConfigApi(configForm.value).then((res: Result) => {
                btnLoading.value = false;
                $utils.successMsg(res.message);
            }, (err: ResultError) => {
                btnLoading.value = false;
                $utils.errorMsg(err);
            })
        }
    })
}

onMounted(() => {
    toInit();
})
</script>

<style scoped>
.form-main {
    padding-top: 40px;
    width: 600px;
}
</style>