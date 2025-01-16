<template>
    <div>
        <a-card title="网站配置" class="card" v-loading="initLoading">
            <div class="card-form-box" :style="`width:${isMobile ? 'calc(100% - 20px)' : '400px'}`">
                <a-form :model="configForm" ref="configFormRef">
                    <a-form-item :label-col-flex="labelColFlex" label="网站名称" field="system_name">
                        <a-input v-model="configForm.system_name" placeholder="请输入网站名称" allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="网站版本" field="system_version">
                        <a-input v-model="configForm.system_version" placeholder="请输入网站版本" allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="网站logo" field="system_logo">
                        <upload-btn v-model="configForm.system_logo" count="1"></upload-btn>
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="网站图标" field="system_icon">
                        <upload-btn v-model="configForm.system_icon" count="1"></upload-btn>
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="网站URL地址" field="web_domain">
                        <a-input v-model="configForm.web_domain" placeholder="请输入网站URL" allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex" label="Copyright" field="copyright">
                        <a-input v-model="configForm.copyright" placeholder="请输入copyright" allow-clear />
                    </a-form-item>
                    <a-form-item :label-col-flex="labelColFlex"  v-permission="'system-config-system-update'">
                        <a-button type="primary" @click="onSave" :loading="btnLoading"
                            :disabled="btnLoading">保存</a-button>
                    </a-form-item>
                </a-form>

            </div>
        </a-card>
    </div>
</template>


<script lang="ts" setup>
import { getCurrentInstance, onMounted, ref } from "vue";
import { getConfigApi, saveConfigApi } from "@/api/system/config";
import { Result, ResultError } from "@/types";
import { useAppStore } from "@/store";
import { storeToRefs } from "pinia";
import { ValidatedError } from "@arco-design/web-vue";

const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const labelColFlex = ref<string>("80px");

const configFormRef = ref<HTMLElement>();

interface ConfigFormType {
    system_name:string;
    system_version:string;
    system_logo:string;
    system_icon: string;
    web_domain:string;
    copyright: string;
}

const configForm = ref<ConfigFormType>({
    system_name: "",
    system_version: "",
    system_logo: "",
    system_icon: "",
    web_domain: "",
    copyright: ""
})

const initLoading = ref<boolean>(false)

const toInit = () => {
    initLoading.value = true
    getConfigApi('default').then((res: Result) => {
        configForm.value = res.data
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
            btnLoading.value = true;
            saveConfigApi(configForm.value).then((res: Result) => {
                btnLoading.value = false;
                useAppStore().getSystemInfo();
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
.card-form-box {
    width: 400px;
}
</style>