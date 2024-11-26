<template>
    <a-modal v-model:visible="visible" :width="isMobile ? 'calc(100% - 20px)' : '1168px'" @BeforeCancel="close" class="modal media-select-modal"
        titleAlign="start" :footer="Number(count) > 1 || count == -1" :top="useSetting().ModalTop" :align-center="false"
        
        render-to-body>
        <template #title>
            <a-radio-group type="button" v-model="mediaType">
                <template v-for="(item, index) in typeData" :key="index">
                    <a-radio :disabled="item.value != props.type" :value="item.value">{{ item.name }}</a-radio>
                </template>
            </a-radio-group>
        </template>
        <template #footer>
            <a-space>
                <a-button v-btn @click="close">取消</a-button>
                <a-button v-btn type="primary" @click="onSelectOk">确定</a-button>
            </a-space>
        </template>
        <div class="media-select-modal-main" v-if="visible">
            <mediaFiles :groupWidth="160" :type="mediaType" @change="selectChange" :count="count" ref="mediaModalRef"
                :isModal="true">
            </mediaFiles>
        </div>
    </a-modal>
</template>
<script lang="ts">
export default {
    name: "mediaSelectModal",
};
</script>
<script setup lang="ts">
import { ref, getCurrentInstance, nextTick } from "vue";
import mediaFiles from "@/components/media/list.vue";
import { useSetting } from "@/hooks/useSetting";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";

const { isMobile } = storeToRefs(useAppStore());

const props = withDefaults(
    defineProps<{
        count?: number | string;
        type?: string;
    }>(),
    {
        count: 1,
        type: 'image'
    }
);

const { proxy } = getCurrentInstance() as any

const emit = defineEmits(["change", "close"]);

interface typeItem {
    name: string;
    value: string;
}

const mediaType = ref<string>(props.type);

const typeData = ref<typeItem[]>([{
    name: '图片',
    value: 'image'
}, {
    name: '视频',
    value: 'video'
}, {
    name: '音频',
    value: 'audio'
}]);

const modelSelectData = ref<string | number | string[] | number[] | any>([])

const modalStatus = ref<boolean>(false);

const visible = ref<boolean>(false);

const mediaModalRef = ref<HTMLElement>();

const open = (selectedData: string | string[] = [], type?: string) => {
    if (modalStatus.value == true) {
        return;
    }
    if (type) {
        mediaType.value = type
    }
    modalStatus.value = true;
    visible.value = true;
    nextTick(() => {
        proxy?.$refs['mediaModalRef']?.open(selectedData);
    })
};

const changeType = (val: any) => {
    mediaType.value = val
    proxy?.$refs['mediaModalRef']?.toTypeInit(mediaType.value)
}

const onSelectOk = () => {
    proxy?.$refs['mediaModalRef']?.setYes()
};

const selectChange = (selectedData: string | string[]) => {
    modelSelectData.value = selectedData
    emit("change", selectedData);
    close()
}

const close = () => {
    modalStatus.value = false;
    visible.value = false;
    proxy?.$refs['mediaModalRef']?.close();
    emit("close");
    return true;
};

defineExpose({ open });
</script>
<style >
.media-select-modal>.arco-modal-wrapper>.arco-modal>.arco-modal-body {
    height: 584px;
    padding: 0;
}

.media-select-modal>.arco-modal-wrapper>.arco-modal>.arco-modal-body .media-select-modal-main {
    height: 100%;
}
</style>