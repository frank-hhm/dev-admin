<template>
    <div>
        <div @click="onOpen">
            <slot></slot>
        </div>
        <a-modal v-model:visible="visible" hide-title width="860px" @BeforeCancel="onClose"
            :align-center="false" titleAlign="start" esc-to-close unmount-on-close render-to-body>
            <div class="video-mian" v-if="visible">
                <video :src="videoSrc" ref="videoPlayRef" :poster="cover" class="video-box" @error="onError" controls
                    :playsinline="true"></video>
            </div>
            <template #footer>
                <div>
                    <div>
                        <template v-if="isError">
                            <span>此视频格式不支持在线预览，请<a-button type="text" @click="onDownload">下载</a-button>后播放</span>
                        </template>
                    </div>
                    <a-space>
                        <a-button @click="onDownload">下载</a-button>
                        <a-button v-btn @click="onClose">关闭</a-button>
                    </a-space>
                </div>
            </template>
        </a-modal>
    </div>
</template>

<script lang="ts">
export default {
    name: "video-play-modal",
};
</script>
<script lang="ts" setup>
import { ref, getCurrentInstance, reactive } from "vue";

const {
    proxy,
    proxy: { $utils }
} = getCurrentInstance() as any;

const props = withDefaults(
    defineProps<{
        src?: string;
        cover?: string;
        height?: string;
    }>(),
    {
        src: "",
        cover: ""
    }
);

const videoSrc = ref<string>(props.src)

const videoCover = ref<string>(props.cover)

const visible = ref<boolean>(false)

const videoPlayRef = ref<HTMLElement>()

const isError = ref<boolean>(false)

const onError = (e: any) => {
    isError.value = true
}

const onDownload = () => {
    $utils.downloadVideo(videoSrc.value)
}

const onOpen = () => {
    visible.value = true
}

const onClose = () => {
    isError.value = false
    visible.value = false
    return true
}
const open = (src: string, cover: string = '') => {
    videoSrc.value = src;
    videoCover.value = cover;
    onOpen()
}

const close = () => {
    proxy?.$refs['videoPlayRef'].unmount()
    onClose()
}

defineExpose({ open, close });
</script>
<style scoped>
.video-mian {
    height: 600px;
    width: 100%;
}

.video-box {
    height: 100%;
    width: 100%;
}
</style>