<template>
    <a-modal title="修改邮箱" @BeforeOk="onCreateOk" @BeforeCancel="close" :width="isMobile ? 'calc(100% - 20px)' : '360px'"
        :top="useSetting().ModalTop" class="modal" v-model:visible="visible" :align-center="false" title-align="start"
        render-to-body>
        <a-form :model="createForm" layout="vertical" ref="createRef" :rules="createRules">
            <a-form-item :label-col-flex="labelColFlex" label="验证码" field="captcha_code">
                <a-input v-model="createForm.captcha_code" placeholder="请输入验证码" allow-clear maxlength="4" size="large">
                    <template #append>
                        <a-spin :loading="captchaLoading">
                            <div @click="toCaptcha" class="captcha-img">
                                <img v-if="captchaImage" :src="captchaImage" />
                            </div>
                        </a-spin>
                    </template>
                </a-input>
            </a-form-item>
            <a-form-item :label-col-flex="labelColFlex" :label="'原邮箱验证码(' + adminInfo.email + ')'" field="code">
                <a-input placeholder="请输入邮箱验证码" v-model="createForm.code">
                    <template #append>
                        <a-button type="text" size="mini" :disabled="isCodeStatus || codeLoading" @click="getCode"
                            :loading="codeLoading">{{ codeText }}</a-button>
                    </template>
                </a-input>
            </a-form-item>
            <a-form-item :label-col-flex="labelColFlex" label="新邮箱" field="email">
                <a-input v-model="createForm.email" placeholder="请输入邮箱"></a-input>
            </a-form-item>
        </a-form>
        <template #footer>
            <a-space>
                <a-button @click="close()">取消</a-button>
                <a-button type="primary" @click="onCreateOk()" :loading="btnLoading"
                    :disabled="btnLoading">确定</a-button>
            </a-space>
        </template>
    </a-modal>
</template>

<script lang="ts">
export default {
    name: "update-email",
};
</script>
<script lang="ts" setup>
import { ref, getCurrentInstance, nextTick, reactive, onMounted } from "vue";
import { EnumItemType, Result, ResultError } from "@/types";
import { useEnumStore, useAppStore, useAdminStore } from '@/store';
import { useSetting } from "@/hooks/useSetting";
import { storeToRefs } from "pinia";
import { ValidatedError } from "@arco-design/web-vue";
import { getCaptchaApi, getEmailCodeApi } from "@/api/common";
import { uploadEmailApi } from "@/api/system/admin";

const { adminInfo } = storeToRefs(useAdminStore());

const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const labelColFlex = ref<string>("80px");

const emit = defineEmits(["success"]);

const visible = ref<boolean>(false);

const createRef = ref<HTMLElement>();

const createForm = ref<any>({
    email: "",
    captcha_uniqid: "",
    captcha_code: "",
    code: null,
});

const createRules: any = reactive({
    email: [
        { required: true, message: "邮箱不能为空！", trigger: "blur" },
        {
            validator: (value: string | undefined, callback: (error?: string) => void) => {
                console.log($utils.isEmail(value))
                if (!$utils.isEmail(value)) {
                    callback('邮箱格式不正确')
                } else {
                    callback()
                }
            }, trigger: "blur"
        },
    ],
    captcha_code: [
        { required: true, message: "请输入验证码", trigger: "blur" },
    ],
    code: [
        { required: true, message: "请输入邮箱验证码", trigger: "blur" },
    ],
});

const codeText = ref<string>("获取验证码");

const codeLoading = ref<boolean>(false);

const getCode = () => {
    // return false;
    proxy?.$refs['createRef']?.validateField(['captcha_code'], (error: undefined | Record<string, ValidatedError>) => {
        if (error === undefined) {
            if (codeLoading.value === true) {
                return
            }
            codeLoading.value = true;
            getEmailCodeApi({
                email: adminInfo.value.email,
                captcha_code: createForm.value.captcha_code,
                captcha_uniqid: createForm.value.captcha_uniqid,
                type: 'again-email'
            }).then((res: Result) => {
                $utils.successMsg(res.message);
                setCodeTimer();
                codeLoading.value = false;
            }).catch((err: ResultError) => {
                if (err.code === 702) {
                    toCaptcha();
                }
                $utils.errorMsg(err);
                codeLoading.value = false;
            });
        }
    })
};

const codeTimer = ref<unknown>(null);

const codeCount = ref<number>(60);

const isCodeStatus = ref<boolean>(false);

const setCodeTimer = () => {
    isCodeStatus.value = true;
    codeCount.value = 60
    codeText.value = codeCount.value + "s"
    codeTimer.value = setInterval(() => {
        if (codeCount.value <= 0) {
            codeText.value = "获取验证码"
            if (codeTimer.value) {
                clearInterval(Number(codeTimer.value));
            }
            codeTimer.value = null
            isCodeStatus.value = false;
            return
        }
        codeCount.value--
        codeText.value = codeCount.value + "s"
    }, 1000)
}


const captchaImage = ref<string>("");

const captchaLoading = ref<boolean>(false);

const toCaptcha = () => {
    captchaLoading.value = true;
    getCaptchaApi()
        .then((res: Result) => {
            captchaImage.value = res.data.data;
            createForm.value.captcha_uniqid = res.data.uniqid;
            captchaLoading.value = false;
        })
        .catch((err: ResultError) => {
            captchaLoading.value = false;
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
            uploadEmailApi(createForm.value).then((res: Result) => {
                useAdminStore().initInfo()
                $utils.successMsg(res.message);
                setTimeout(() => {
                    close()
                }, 300);
            }).catch((err: ResultError) => {
                $utils.errorMsg(err);
                btnLoading.value = false;
            });
        }
    });
};

const open = () => {
    toCaptcha();
    btnLoading.value = false;
    visible.value = true;
};

const close = () => {
    proxy?.$refs['createRef']?.resetFields();
    visible.value = false;
    btnLoading.value = false;
    return true;
};

onMounted(() => {
});

defineExpose({ open });
</script>

<style scoped>
.captcha-img {
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    text-align: center;
}

.captcha-img img {
    width: 80px;
    height: 34px;
    cursor: pointer;
    user-select: none;
    display: block;
}
</style>