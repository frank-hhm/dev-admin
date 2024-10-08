<template>
    <div>
        <a-card title="个人中心">
            <template #extra>
                <a-space>
                    <a-popconfirm content="确定要退出登录吗?" @ok="outLogin">
                        <a-button size="mini" type="text" status="danger">退出</a-button>
                    </a-popconfirm>
                    <adminPassModal ref="adminPassModalRef"></adminPassModal>
                    <a-button size="mini" type="text" @click="onUpdatePass">修改密码</a-button>
                </a-space>
            </template>
            <div class="detail-main">
                <div class="avatar-main">
                    <a-avatar @click="onUpdateAvatar" shape="square" :size="80">
                        <img :src="adminInfo?.avatar" />
                        <template #trigger-icon>
                            <IconEdit />
                        </template>
                    </a-avatar>
                    <div v-if="avatarLoading" class="avatar-progress">
                        <a-progress size="mini" :percent="avatarProgress.percent / 100" />
                    </div>

                    <img-cropper ref="cropperRef" @change="onChangeAvatar" :autoClose="false"
                        outputType="png"></img-cropper>
                </div>
                <div class="detail-right">
                    <div class="detail-item">
                        <div class="title">账号：</div>
                        <div class="desc">{{ adminInfo.account }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="title">姓名：</div>
                        <div class="desc">{{ adminInfo.real_name }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="title">登录IP：</div>
                        <div class="desc flex ">
                            <div class="fz12">{{ adminInfo.last_ip?.value }}</div>
                            <div class="text-grey ml20 fz12" v-if="adminInfo.last_ip?.text">{{ adminInfo.last_ip?.text
                                }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </a-card>
    </div>
</template>
<script setup lang="ts">
import { getCurrentInstance, ref, onMounted, watch } from "vue";
import { useAdminStore, useAppStore } from "@/store";
import router from "@/router/index";
import { storeToRefs } from "pinia";
import { logoutApi } from '@/api/login';
import { Message } from '@arco-design/web-vue';
import adminPassModal from "@/components/system/admin/update-password.vue";
import { uploadAvatarApi } from "@/api/system/admin";
import { Result, ResultError } from "@/types";

const { adminInfo } = storeToRefs(useAdminStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const cropperRef = ref<HTMLElement>();

const onUpdateAvatar = () => {
    proxy?.$refs['cropperRef']?.open();
}

const avatarLoading = ref<boolean>(false);
const avatarProgress = ref<any>({
    percent: 0,
});

const onChangeAvatar = (file: File) => {
    proxy?.$refs['cropperRef']?.close();
    avatarLoading.value = true;
    let fd = new FormData();
    fd.append('file', file);
    const { progress, request } = uploadAvatarApi(fd)
    avatarProgress.value = progress
    request.then((res: Result) => {
        avatarProgress.value = {
            parent: 100
        }
        useAdminStore().initInfo()
        setTimeout(() => {
            $utils.successMsg(res.message)
            avatarLoading.value = false;
        }, 500);
    }).catch((err: ResultError) => {
        $utils.successMsg(err.message)
        avatarProgress.value = {
            parent: 0
        }
        avatarLoading.value = false;
    });

}

const adminPassModalRef = ref<HTMLElement>();

const onUpdatePass = () => {
    proxy?.$refs["adminPassModalRef"]?.open();
};

const outLogin = () => {
    Message.loading({
        id: 'outlogin',
        content: "正在退出...",
    });
    logoutApi().then(async (res: any) => {
        useAdminStore().resetToken();
        $utils.setStorage("token", null);
        setTimeout(() => {
            Message.success({
                id: 'outlogin',
                content: "退出成功!",
                duration: 1000,
                onClose: () => {
                    toLogin();
                },
            });
        }, 500);
    });
};

const toLogin = () => {
    router.replace({
        path: "/login",
        query: {
            redirect: router.currentRoute.value.fullPath,
        },
    });
};

</script>
<style scoped>
.detail-main {
    display: flex;

}

.detail-right {
    margin-left:30px;
}

.detail-item {
    display: flex;
    height: 30px;
    line-height: 30px;
    align-items: center;
}

.detail-item .desc {
    font-size: 12px;
}

.detail-item .desc {
    font-size: 16px;
    color: var(--color-text-1);
}

.avatar-main {
    height: 80px;
    width: 80px;
    position: relative;
}

.avatar-progress {
    position: absolute;
    left: calc(50% - 8px);
    top: calc(50% - 8px);
}
</style>
