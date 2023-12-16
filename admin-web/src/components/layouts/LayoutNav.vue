<template>
  <div class="layout-nav">
    <div class="layout-nav-left">
      <div class="layout-nav-logo">
        <img class="logo-image" v-if="systemInfo?.system_logo" :src="systemInfo?.system_logo" alt="logo">
        <span class="name">{{ systemInfo?.system_name }} </span>
        <span class="version">v{{ systemInfo?.system_version || "0.01" }}</span>
      </div>
    </div>
    <div class="layout-nav-right" ref="rightRef">
      <a-button class="mr20" shape="circle" size="mini" @click="onTemplate">
        <icon-sun-fill />
      </a-button>
      <adminPassModal ref="adminPassModalRef"></adminPassModal>
      <a-dropdown v-if="adminInfo?.id">
        <div class="layout-nav-user">
          <a-avatar :size="24" :imageUrl="adminInfo?.avatar"></a-avatar>
          <div class="ml10">
            {{ adminInfo.real_name }}
            ({{ adminInfo.account }})
          </div>
        </div>
        <template #content>
          <a-doption @click="onUpdatePass">修改密码</a-doption>
          <a-doption @click="outLogin">退出登录</a-doption>
        </template>
      </a-dropdown>
      <div v-else>未登录</div>
    </div>
  </div>
</template>
<script lang="ts">
export default {
  name: "LayoutNav",
};
</script>
<script setup lang="ts">
import { getCurrentInstance, ref, onMounted } from "vue";
import { useAdminStore, useAppStore } from "@/store";
import router from "@/router/index";
import { storeToRefs } from "pinia";
import { logoutApi } from '@/api/login';
import { Message } from '@arco-design/web-vue';

const { systemInfo } = storeToRefs(useAppStore());

const { adminInfo } = storeToRefs(useAdminStore());

const emit = defineEmits(["change"]);

const {
  proxy,
  proxy: { $utils },
} = getCurrentInstance() as any;


const outLogin = () => {
  Message.loading({
    id:'outlogin',
    content: "正在退出...",
  });
  logoutApi().then(async (res: any) => {
    useAdminStore().resetToken();
    $utils.setStorage("token", null);
    setTimeout(() => {
      Message.success({
        id:'outlogin',
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

const adminPassModalRef = ref<HTMLElement>();

const onUpdatePass = () => {
  proxy?.$refs["adminPassModalRef"]?.open();
};

const onTemplate = () => {
  useAppStore().setDark(!useAppStore().isDark)
}

const screenWidth = ref();

const rightRef = ref<HTMLElement>();

onMounted(() => {
  screenWidth.value = document.body.clientWidth;
  window.onresize = () => {
    return (() => {
      screenWidth.value = document.body.clientWidth;
    })();
  };
});
</script>
<style scoped>
.layout-nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 50px;
  background: var(--color-bg-1);
  border-bottom: 1px solid var(--color-border-1);
}

.layout-nav .layout-nav-left {
  width: calc(100% - 300px);
  display: flex;
  align-items: center;
  margin-left: 20px;
}

.layout-nav .layout-nav-right {
  margin-right: 20px;
  display: flex;
  align-items: center;
}

.layout-nav .layout-nav-logo {
  max-width: 406px;
  height: 40px;
  line-height: 40px;
  text-align: center;
  display: flex;
  align-items: center;
}

.layout-nav .layout-nav-logo .logo-image {
  height: 30px;
  max-width: 120px;
  margin-right: 10px;
}

.layout-nav .layout-nav-logo .name {
  font-size: 16px;
  color: var(--color-text-1);
}

.layout-nav .layout-nav-logo .version {
  margin-left: 5px;
  font-size: 12px;
  color: var(--color-text-4);
}

.layout-nav .layout-nav-menu {
  display: flex;
  overflow: hidden;
  flex: 1;
  align-items: center;
  -webkit-transition: all 0.1s ease-in-out;
  transition: all 0.1s ease-in-out;
}

.layout-nav-menu-btn {
  width: 40px;
  text-align: center;
  height: 40px;
  line-height: 40px;
  cursor: pointer;
}

.layout-nav-menu-btn:hover {
  color: rgba(var(--primary-1));
}

.layout-nav .layout-nav-menu-item {
  padding: 0 12px;
  font-size: var(--base-size);
  cursor: pointer;
  height: 30px;
  line-height: 30px;
  user-select: none;
  border-radius: 2px;
}

.layout-nav .layout-nav-menu-item:hover {
  color: rgba(var(--primary-1));
}

.layout-nav .layout-nav-menu-item.active {
  color:rgba(var(--primary-1));
}

.layout-nav-user {
  display: flex;
  align-items: center;
  cursor: pointer;
  user-select: none;
  color:var(--color-text-1)
}
</style>