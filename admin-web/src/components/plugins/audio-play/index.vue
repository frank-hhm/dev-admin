<template>
    <!-- <div>
        <el-button link type="primary">{{btn}}</el-button>
    </div>-->
    <a-popover class="audio-popover-main" placement="bottom" :style="{
        width:isMobile?'calc(100% - 20px)':'340px'
    }" trigger="click" @hide="onEnded()"
        @show="onPlay()">
        <div v-if="btn" class="audio-btn">{{ btn }}</div>
        <div v-else class="icon icon-bofang"></div>
        <template #content>
            <div class="audio-player" :class="isMobile?'mobile':''" v-loading="audioLoading">
                <span class="play-time">
                    {{ transTime(audioCurrent) }}/{{ transTime(audioDuration) }}
                </span>
                <div class="play-progress" >
                    <a-slider v-model="playProgress" :show-tooltip="false" @change="progressChange" />
                    <!-- <div class="play-current-progress" :style="{ width: `${playProgress}%` }"></div> -->
                </div>
                <div class="play-icon icon icon-bofang" v-if="!playStatus" @click="onPlay"></div>
                <div class="play-icon icon icon-zanting" v-else @click="onPause"></div>

                <div class="play-voice icon icon-gonggao" v-if="audioVolume === 1" @click="onSetVolume(0)"></div>
                <div class="play-voice icon icon-jingyin" v-else @click="onSetVolume(1)"></div>
            </div>

            <audio ref="audioRef" v-if="status" :src="src" @canplay="onCanplay" @timeupdate="onTimeupdate"></audio>
        </template>
    </a-popover>
</template>
<script lang="ts">
export default {
    name: "dubPlay",
};
</script>
<script lang="ts" setup>
import { ref, getCurrentInstance, onBeforeMount, nextTick } from "vue";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";

const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
} = getCurrentInstance() as any;

const props = withDefaults(
    defineProps<{
        btn?: string;
        src?: string;
    }>(),
    {
        btn: "",
        src: ""
    }
);
onBeforeMount(() => {
    clearInterval(timeInterval.value);
});

const status = ref<boolean>(false)
const speedVisible = ref<boolean>(false); // 设置音频播放速度弹窗

const audioLoading = ref<boolean>(true);
const audioRef = ref<HTMLElement>(); // 音频标签对象
const activeSpeed = ref<number>(1); // 音频播放速度
const audioDuration = ref<number>(0); // 音频总时长
const audioCurrent = ref<number>(0); // 音频当前播放时间
const audioVolume = ref<number>(1); // 音频声音，范围 0-1
const playStatus = ref<boolean>(false); // 音频播放状态：true 播放，false 暂停
const playProgress = ref<number>(0); // 音频播放进度
const timeInterval = ref<any>(null); // 获取音频播放进度定时器

// 音频加载完毕的回调
const onCanplay = () => {
    audioDuration.value = proxy?.$refs['audioRef']?.duration || 0;
    audioLoading.value = false
}
const onPlay = async () => {
    status.value = true
    nextTick(async () => {
        // 音频播放完后，重新播放
        if (playProgress.value === 100) {
            proxy.$refs['audioRef']!.currentTime = 0;
        }
        playStatus.value = true;
        audioDuration.value = proxy?.$refs['audioRef']?.duration;
        await proxy?.$refs['audioRef']?.play();
    })
};

const onTimeupdate = (res: any) => {
    audioCurrent.value = res.target.currentTime
    playProgress.value = (audioCurrent.value / audioDuration.value) * 100;

    if (playProgress.value === 100) {
        onEnded()
    }
}

const onPause = () => {
    proxy?.$refs['audioRef']?.pause();
    playStatus.value = false;
    clearInterval(timeInterval.value);
};

// const onChangeSpeed = (value: number) => {
//     activeSpeed.value = value;
//     // 设置倍速
//     proxy?.$refs['audioRef']?.playbackRate = value;
//     speedVisible.value = false;
// };

const onHandleSpeed = () => {
    speedVisible.value = !speedVisible.value;
};

const progressChange = (val: any) => {
    nextTick(() => {
        proxy.$refs['audioRef']!.currentTime = proxy?.$refs['audioRef']?.duration * val / 100
    })
}

// 结束
const onEnded = () => {
    proxy?.$refs['audioRef']?.pause();
    playStatus.value = false;
    proxy.$refs['audioRef']!.currentTime = 0;
    audioDuration.value = 0
    playProgress.value = 0
};

// 设置声音
const onSetVolume = (value: number) => {
    proxy.$refs['audioRef']!.volume = value;
    audioVolume.value = value;
};


// 音频播放时间换算
const transTime = (value: number) => {
    let time = "";
    let h = parseInt(String(value / 3600)) || 0;
    value %= 3600;
    let m = parseInt(String(value / 60)) || 0;
    let s = parseInt(String(value % 60)) || 0;
    if (h > 0) {
        time = formatTime(h + ":" + m + ":" + s);
    } else {
        time = formatTime(m + ":" + s);
    }
    return time;
};
// 格式化时间显示，补零对齐
const formatTime = (value: string) => {
    let time = "";
    let s = value.split(":");
    let i = 0;
    for (; i < s.length - 1; i++) {
        time += s[i].length == 1 ? "0" + s[i] : s[i];
        time += ":";
    }
    time += s[i].length == 1 ? "0" + s[i] : s[i];

    return time;
};

</script>
<style>
.audio-popover-main .arco-trigger-content {
    padding: 6px !important;
}

.audio-popover-main .arco-popover-content {
    margin-top: 0 !important;
}
</style>

<style  scoped>
.audio-player {
    width: 300px;
    height: 32px;
    padding: 0 10px;
    display: flex;
    align-items: center;
}
.audio-player.mobile{
    width:calc( 100% - 20px);
}
.play-icon {
    width: 30px;
    height: 30px;
    line-height: 30px;
    margin-right: 10px;
    cursor: pointer;
    font-size: 20px;
    color: var(--color-text-1);
}

.play-current-progress {
    height: 4px;
    background: var(--base-default);
    border-radius: 2px;
    position: absolute;
    top: 0;
    left: 0;
}

.play-time {
    width: 72px;
    display: inline-block;
    margin-right: 16px;
    font-size: 12px;
}

.play-progress {
    width: 160px;
    margin-right: 16px;
    position: relative;
}

.play-voice {
    width: 30px;
    height: 30px;
    line-height: 30px;
    cursor: pointer;
    color: var(--color-text-1);
    font-size: 20px;
}

.audio-btn {
    cursor: pointer;
    color: rgba(var(--primary-6));
}
</style>