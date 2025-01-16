<template>
    <div class="login-body">
        <Particles id="tsparticles" v-if="initLoading" :particlesInit="particlesInit" :particlesLoaded="particlesLoaded"
            :options="getOptions()" />
        <div class="login-box">
            <div class="login-box-form">
                <div class="system-name">
                    <img class="logo-image" v-if="systemInfo?.system_logo" :src="systemInfo?.system_logo" alt="logo">
                    <div class="title">
                        <div class="fw">{{ systemInfo?.system_name }}</div>
                        <div class="ctitle">v{{ systemInfo?.system_version || '0.01' }}</div>
                    </div>

                </div>
                <a-form :model="loginForm" layout="vertical" ref="loginRef" :rules="loginRules">
                    <a-form-item field="account" hide-label hide-asterisk class="login-main-form-item">
                        <a-input v-model="loginForm.account" placeholder="请输入账号或邮箱" size="large">
                            <template #prefix>
                                <icon-user />
                            </template>
                        </a-input>
                    </a-form-item>
                    <a-form-item field="password" hide-label hide-asterisk class="login-main-form-item">
                        <a-input-password v-model="loginForm.password" placeholder="请输入密码" allow-clear size="large">
                            <template #prefix>
                                <icon-lock />
                            </template>
                        </a-input-password>
                    </a-form-item>
                    <a-form-item field="captcha_code" hide-label hide-asterisk
                        class="login-main-form-item default-append">
                        <a-input v-model="loginForm.captcha_code" placeholder="请输入验证码" allow-clear maxlength="4"
                            size="large">
                            <template #prefix>
                                <icon-dice />
                            </template>
                            <template #append>
                                <a-spin :loading="captchaLoading">
                                    <div @click="toLoginCaptcha" class="login-captcha-img">
                                        <img v-if="captchaImage" :src="captchaImage" />
                                    </div>
                                </a-spin>
                            </template>
                        </a-input>
                    </a-form-item>
                    <a-form-item class="login-main-form-item">
                        <a-button type="primary" :loading="loginLoad" long @click="toLogin" size="large">登录</a-button>
                    </a-form-item>
                </a-form>
                <div class="flex between items-center">
                    <div>
                        <a-checkbox v-model="isCacheLogin" default-checked>记住密码</a-checkbox>
                    </div>
                    <div>
                        <a-button type="text" size="mini" @click="onForgetPassword">
                            找回密码
                        </a-button>
                        <forgetPassword ref="forgetPasswordRef"></forgetPassword>
                    </div>
                </div>
                <div class="mt10 flex center">
                    <a-button shape="circle" size="mini" @click="onTemplate">
                        <icon-sun-fill />
                    </a-button>
                </div>
            </div>
        </div>
        <copyright bottom="40px"></copyright>
    </div>
</template>
<script lang="ts" setup>
import { ref, getCurrentInstance, reactive, onMounted, nextTick } from "vue";
import type { Result, ResultError } from "@/types";
import { loginApi, } from "@/api/login";
import { getCaptchaApi } from "@/api/common";
import forgetPassword from "./forget-password.vue";

import {
    useAppStore,
    useAdminStore,
    useMenusStore,
    usePermissionStore,
} from "@/store";
import { getCacheLogin, setCacheLogin, setToken } from "@/utils";
import { storeToRefs } from "pinia";
import common from "./common";
import router from "@/router";
import { Notification } from '@arco-design/web-vue';

const { systemInfo } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const cacheLogin = ref<any>(getCacheLogin())

const loginForm = reactive({
    account: cacheLogin.value?.account || "",
    password: cacheLogin.value?.password || "",
    captcha_uniqid: "",
    captcha_code: "",
});

const loginRules = {
    account: [{ required: true, message: "请输入账号", trigger: "blur" }],
    password: [{ required: true, message: "请输入密码", trigger: "blur" }],
    captcha_code: [{ required: true, message: "请输入验证码", trigger: "blur" }],
};

const loginRef = ref<HTMLElement>();

const loginLoad = ref<boolean>(false);

const permission = usePermissionStore();

const toLogin = () => {
    if (!proxy?.$refs['loginRef']) return;
    if (loginLoad.value == true) {
        return false;
    }
    proxy?.$refs['loginRef']?.validate((valid: boolean) => {
        if (!valid) {
            loginLoad.value = true;
            loginApi(loginForm)
                .then((res: Result) => {
                    useAdminStore().setInfo(res.data.user_info);
                    useMenusStore().setMenus(res.data.menus.menusList);
                    setToken(res.data.token, res.data.expires_time);
                    permission.setHomeAction(res.data.menus.homePath);
                    permission.setRoleAction(res.data.menus.action);
                    if (isCacheLogin.value) {
                        setCacheLogin({
                            account: window.btoa(loginForm.account),
                            password: window.btoa(loginForm.password)
                        })
                    } else {
                        setCacheLogin(null);
                    }
                    Notification.success({
                        title: "登录成功",
                        content: "正在进入...",
                        duration: 1500,
                        onClose: () => {
                            let path: any = res.data.menus.homePath || "/";
                            if (router.currentRoute.value.query.redirect) {
                                path = permission.getRoleActionPath(
                                    String(router.currentRoute.value.query.redirect)
                                );
                            }
                            router.push({
                                path: path,
                            });
                        },
                    });
                })
                .catch((err: ResultError) => {
                    loginLoad.value = false;
                    if (err.data?.code == 702) {
                        loginForm.captcha_code = "";
                        toLoginCaptcha();
                    }
                    $utils.errorMsg(err);
                });
        }
    });
};
const captchaImage = ref<string>("");

const captchaLoading = ref<boolean>(false);

const toLoginCaptcha = () => {
    captchaLoading.value = true;
    getCaptchaApi()
        .then((res: Result) => {
            captchaImage.value = res.data.data;
            loginForm.captcha_uniqid = res.data.uniqid;
            captchaLoading.value = false;
        })
        .catch((err: ResultError) => {
            captchaLoading.value = false;
            $utils.errorMsg(err);
        });
};

const keydownEvent = () => {
    document.onkeydown = (e) => {
        if (e.defaultPrevented) {
            return;
        }
        if (e.keyCode === 13) {
            toLogin();
        }
    };
};

onMounted(() => {
    toLoginCaptcha();
    keydownEvent();
});

const {
    particlesInit,
    particlesLoaded,
    getOptions
} = common(() => {

});

const initLoading = ref<boolean>(true);

const onTemplate = () => {
    if (loginLoad.value) {
        return;
    }
    initLoading.value = false;
    useAppStore().setDark(!useAppStore().isDark)
    nextTick(() => {
        initLoading.value = true;
    });
}

const isCacheLogin = ref<boolean>(true);

const forgetPasswordRef = ref<HTMLElement>()

const onForgetPassword = () => {
    proxy?.$refs['forgetPasswordRef']?.open();
}

</script>


<style scoped lang="scss">
.login-body {}

.login-box {
    width: 320px;
    height: 400px;
    display: flex;
    position: fixed;
    top: calc(50% - 250px);
    left: calc(50% - 160px);
    z-index: 9;
    box-shadow: 0 10px 75px 0 rgba(0, 0, 0, 0.05), 0 20px 87px 0 rgba(0, 0, 0, 0.07), 0 30px 94px 0 rgba(0, 0, 0, 0.06), 0 40px 100px 0 rgba(0, 0, 0, 0.03);
    background: var(--color-bg-2);
    border-radius: 4px;
}

.login-box-form {
    position: relative;
    width: 100%;
    padding: 40px 40px 0;
    z-index: 9;
}

.login-box-form .system-name {
    color: var(--color-text-1);
    font-size: 16px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
}

.login-box-form .system-name .logo-image {
    height: 30px;
    max-width: 120px;
    margin-right: 10px;
}

.login-box-form .title {
    display: flex;
    align-items: baseline;
}

.login-main-form-item {
    // margin-bottom: 20px;
}

.login-box-form .ctitle {
    color: var(--color-text-3);
    font-size: 12px;
    margin-left: 5px;
}

.login-btn {
    width: 100%;
}

.login-captcha-img {
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    text-align: center;
}

.login-captcha-img img {
    width: 80px;
    height: 34px;
    cursor: pointer;
    user-select: none;
    display: block;
}

canvas {
    position: fixed;
    width: 100%;
    height: 100%;
    user-select: none;
    background: #fff;
    cursor: pointer;
    opacity: .1;
    z-index: 1;
}
</style>