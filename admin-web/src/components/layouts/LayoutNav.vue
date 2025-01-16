<template>
  <div class="layout-nav" :class="isMobile ? 'mobile' : ''">
    <div class="layout-nav-left">
      <div class="layout-nav-logo">
        <img class="logo-image" id="logoImage" v-if="systemInfo?.system_logo" :src="systemInfo?.system_logo" />
        <div class="layout-nav-name-wrap">
          <div class="name">{{ systemInfo?.system_name }} </div>
          <div class="version">v{{ systemInfo?.system_version || "0.01" }}</div>
        </div>
      </div>
    </div>
    <div class="layout-nav-right" ref="rightRef">
      <a-button class="mr20" v-if="!isMobile" shape="circle" size="mini" @click="onFull">
        <icon-expand v-if="!isFull" />
        <icon-shrink v-else />
      </a-button>
      <adminPassModal ref="adminPassModalRef"></adminPassModal>
      <a-dropdown v-if="adminInfo?.id">
        <div class="layout-nav-user">
          <a-avatar :size="24" :imageUrl="adminInfo?.avatar"></a-avatar>
          <div class="ml10" v-if="!isMobile">
            {{ adminInfo.real_name }}
            ({{ adminInfo.account }})
          </div>
        </div>
        
        <template #content>
          <a-doption @click="onDetail">个人中心</a-doption>
          <a-doption @click="onUpdatePass">修改密码</a-doption>
          <a-doption @click="outLogin">退出登录</a-doption>
        </template>
      </a-dropdown>
      <div v-else>未登录</div>
      <selectBtn></selectBtn>
    </div>
  </div>
</template>
<script lang="ts">
export default {
  name: "LayoutNav",
};
</script>
<script setup lang="ts">
import { getCurrentInstance, ref, onMounted, watch, onBeforeUnmount } from "vue";
import { useAdminStore, useAppStore, useWebsocketStore } from "@/store";
import router from "@/router/index";
import { storeToRefs } from "pinia";
import { logoutApi } from '@/api/login';
import { Message } from '@arco-design/web-vue';
import adminPassModal from "@/components/system/admin/update-password.vue";
import selectBtn from "@/components/layouts/select-btn.vue";
import { Result } from "@/types";

const { systemInfo, isMobile } = storeToRefs(useAppStore());

const { adminInfo } = storeToRefs(useAdminStore());

const emit = defineEmits(["change"]);

const {
  proxy,
  proxy: { $utils },
} = getCurrentInstance() as any;

const logoImageEl = ref<HTMLElement | null>();

onMounted(() => {
  logoImageEl.value = document.getElementById("logoImage");
  logoImageEl.value?.addEventListener("error", () => {
    onImageError()
  });
});

const onImageError = () => {
  if (logoImageEl.value) {
    logoImageEl.value.style.display = "none";
  }
}

const isFull = ref<boolean>(false);

const onFull = () => {
  if (document.fullscreenElement) {
    document.exitFullscreen();
  } else {
    document.body.requestFullscreen().catch((err) => {
      console.error('Error attempting to enable full screen', err);
    });
  }
};
const handleFullScreenChange = () => {
  isFull.value = !!document.fullscreenElement;
};

const outLogin = () => {
  Message.loading({
    id: 'outlogin',
    content: "正在退出...",
  });
  logoutApi().then(async (res: Result) => {
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

const adminPassModalRef = ref<HTMLElement>();

const onUpdatePass = () => {
  console.log(proxy?.$refs["adminPassModalRef"])
  proxy?.$refs["adminPassModalRef"]?.open();
};

const screenWidth = ref();

const rightRef = ref<HTMLElement>();

onMounted(() => {
  screenWidth.value = document.body.clientWidth;
  window.onresize = () => {
    return (() => {
      screenWidth.value = document.body.clientWidth;
    })();
  };
  document.addEventListener('fullscreenchange', handleFullScreenChange);
  // websocketOpenCallback();
});

const onDetail = () => {
  router.push("/detail");
}


const noticeList = ref<any[]>([])

const websocketMessageCallback = ({
  detail
}: any) => {
  if (detail?.action == 'notice') {
    noticeList.value = detail.data;
  }
}

const messageTimer = ref<any>(null)

const websocketOpenCallback = () => {
  if (useWebsocketStore().ws) {
    $utils.eventsListener('websocketMessage', websocketMessageCallback);
    useWebsocketStore().sendMessage({
      action: "notice"
    })
    messageTimer.value = setInterval(() => {
      useWebsocketStore().sendMessage({
        action: "notice"
      })
    }, 5000)
  } else {
    setTimeout(() => {
      if (messageTimer.value) {
        clearInterval(messageTimer.value)
        messageTimer.value = null
      }
      websocketOpenCallback()
    }, 5000)
  }
}

onBeforeUnmount(() => {
  document.removeEventListener('fullscreenchange', () => { });
  if (messageTimer.value) {
    clearInterval(messageTimer.value)
    messageTimer.value = null
  }
})

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
  height: 36px;
  max-width: 120px;
  margin-right: 6px;
}
.layout-nav .layout-nav-logo .layout-nav-name-wrap{
  display: flex;
  align-items: center;
  max-width: 280px;
}

.layout-nav .layout-nav-logo .name {
  font-size: 16px;
  color: var(--color-text-1);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
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
  font-size: var(--base-size-default);
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
  color: rgba(var(--primary-1));
}

.layout-nav-user {
  display: flex;
  align-items: center;
  cursor: pointer;
  user-select: none;
  color: var(--color-text-1)
}


.layout-nav.mobile .layout-nav-left {
  max-width: 140px;
  margin-left: 4px;
}

.layout-nav.mobile .layout-nav-logo {
  max-width: 140px;
}
.layout-nav.mobile .layout-nav-logo .layout-nav-name-wrap{
  display: block;
  max-width: 100px;
  padding: 10px 0;
}
.layout-nav.mobile .layout-nav-logo .layout-nav-name-wrap>div{
  line-height: 18px;
  text-align: left;
}

</style>