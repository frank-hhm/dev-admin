<template>
    <div class="upload-select-box" ref="uploadSelectBoxRef">
        <!-- 图片 -->
        <template v-if="type == 'image'">
            <!-- 单个 -->
            <template v-if="count == 1">
                <div class="upload-select-btn" :style="styles" @click="onSelectMedia">
                    <template v-if="selectData == ''">
                        <i class="icon icon-jia"></i>
                    </template>
                    <template v-else>
                        <a-image ref="imageRef" height="100%" width="100%" show-loader :preview="false"
                            :src="selectData" fit="cover" />
                        <div class="upload-select-del" @click.stop="onDelete(-1)">
                            <div class="icon icon-cuo"></div>
                        </div>
                        <div class="upload-select-bottom">
                            <div class="btn" @click.stop="onPreview(0)">查看</div>
                            <div class="btn">修改</div>
                        </div>
                    </template>
                </div>
            </template>
            <!-- 多个 -->
            <template v-else>
                <!-- 已选素材 -->
                <template v-for="(item, index) in selectData" :key="index">
                    <div class="upload-select-btn" :style="styles" draggable="true" @dragstart="dragstart(item)"
                        @dragenter="dragenter(item, $event)" @dragend="dragend(item, $event)"
                        @dragover="dragover($event)">
                        <a-image height="100%" width="100%" show-loader :preview="false" :src="item" fit="cover" />
                        <div class="upload-select-del" @click.stop="onDelete(index, item)">
                            <div class="icon icon-cuo"></div>
                        </div>
                        <div class="upload-select-bottom">
                            <div class="btn" @click.stop="onPreview(index)">查看</div>
                            <div class="btn">修改</div>
                        </div>
                    </div>
                </template>
                <div class="upload-select-btn" :style="styles" @click="onSelectMedia">
                    <i class="icon icon-jia"></i>
                </div>
            </template>
            <a-image-preview-group :srcList="previewList" v-model:visible="previewVisblie"
                v-model:current="previewIndex" />
        </template>
        <template v-else-if="type == 'video'">
            <a-input v-model="selectData" clearable :disabled="disabled" placeholder="请选择视频">
                <template #append>
                    <a-button @click.stop="onSelectMedia" type="primary" class="upload-select-btn">选择</a-button>
                </template>
            </a-input>
        </template>
        <mediaSelectModal ref="mediaSelectModalRef" @change="selectChange" v-if="isInit" :type="type" :count="count">
        </mediaSelectModal>
    </div>
</template>
<script lang="ts">
export default {
    name: "upload-btn",
};
</script>
<script setup lang="ts">
import {
    ref,
    computed,
    watch,
    getCurrentInstance,
    nextTick,
    onMounted
} from "vue";

import mediaSelectModal from "@/components/media/select-modal.vue";

const { proxy } = getCurrentInstance() as any;

const props = withDefaults(
    defineProps<{
        type?: string;
        width?: string;
        height?: string;
        modelValue?: string | number | string[] | number[] | any;
        count?: number | string;
        padding?: string;
        disabled?: boolean;
        ratio?: boolean | string | number;
    }>(),
    {
        type: "image",
        width: "",
        height: "98px",
        modelValue: "",
        count: 1,
        padding: '5px',
        disabled: false,
        ratio: false
    }
);
const emit = defineEmits(["change", "update:modelValue"]);

const mediaSelectModalRef = ref<HTMLElement>();

const selectData: string | number | string[] | number[] | any = ref(
    props.count == 1 ? props.modelValue.toString() : props.modelValue
);

const uploadSelectBoxRef = ref<HTMLElement>()

const styles = computed(() => {
    let width = props.width ? props.width : props.height, height:any = props.height;
    if (props.ratio !== false && props.ratio) {
        if (width == '100%') {
            height = Number(uploadSelectBoxRef.value?.offsetWidth) * Number(props.ratio);
        } else {
            height = Number(width) * Number(props.ratio);
        }
    }
    if(typeof height == 'number'){
        height = height + 'px';
    }
    if(typeof width == 'number'){
        width = width + 'px';
    }
    return {
        height: height,
        width: width,
        padding: props.padding,
        marginRight: props.count == 1 ? '' : '5px',
        marginBottom: props.count == 1 ? '' : '5px',
    };
});

const isInit = ref<boolean>(false);

const onSelectMedia = () => {
    isInit.value = true;
    nextTick(() => {
        proxy?.$refs["mediaSelectModalRef"]?.open(selectData.value);
    });
};

const selectChange = (data: any) => {
    selectData.value = data;
    emit("update:modelValue", data);
    emit("change", data);
};

const onDelete = (index: number, url: string = "") => {
    if (index == -1) {
        selectData.value = "";
    } else {
        selectData.value.splice(index, 1);
    }
    emit("update:modelValue", selectData.value);
    emit("change", selectData.value);
};

watch(
    () => props.modelValue,
    () => {
        selectData.value = props.modelValue;
        emit("update:modelValue", selectData.value);
        emit("change", selectData.value);
    }
);

watch(
    () => selectData.value,
    () => {
        emit("update:modelValue", selectData.value);
        emit("change", selectData.value);
    }
);


const dragoverOldItem = ref<any>();

const dragoverNewItem = ref<any>();

const dragstart = (value: any) => {
    dragoverOldItem.value = value;
};

// 停止拖拽时触发
const dragend = (value: any, e: any) => {
    // 如果位置没有发生改变 什么也不做
    if (dragoverOldItem.value !== dragoverNewItem.value) {
        let oldIndex = selectData.value.indexOf(dragoverOldItem.value);
        let newIndex = selectData.value.indexOf(dragoverNewItem.value);

        let newItems = [...selectData.value];
        newItems.splice(oldIndex, 1);
        newItems.splice(newIndex, 0, dragoverOldItem.value);
        selectData.value = [...newItems];
        // 如果位置发生了改变
    }
};

// 记录移动过程中信息
const dragenter = (value: any, e: any) => {
    dragoverNewItem.value = value;
    e.preventDefault();
};

// 拖动事件（主要是为了拖动时鼠标光标不变为禁止）
const dragover = (e: any) => {
    e.preventDefault();
};

const imageRef = ref<HTMLElement>();


const previewVisblie = ref<boolean>(false)

const previewIndex = ref<number>(0)

const previewList = ref<string[]>([])

const onPreview = (index: number) => {
    previewVisblie.value = true
    previewIndex.value = index
    if (!index) {
        previewList.value = [selectData.value]
    } else {
        previewList.value = selectData.value
    }
}

const closeViewer = () => {
    previewVisblie.value = false
}

onMounted(() => {
    nextTick(() => {
        emit("update:modelValue", props.modelValue);
    })
})
</script>
<style scoped>
.upload-select-box {
    display: flex;
    flex-wrap: wrap;
    max-width: 100%;
    width: 100%;
}

.upload-select-btn {
    background: var(--color-secondary);
    border-radius: var(--base-radius-small);
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    color: rgba(var(--gray-6));
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.upload-select-btn .icon {
    font-size: 12px;
}

.upload-select-del {
    position: absolute;
    top: 4px;
    right: 4px;
    color: #fff;
    width: 14px;
    height: 14px;
    line-height: 14px;
    cursor: pointer;
    border-radius: var(--base-radius-small);
    background: rgba(0, 0, 0, 0.6);
    text-align: center;
    opacity: 0;
    transition: all 0.3s;
}


.upload-select-del .icon {
    font-size: 12px;
}

.upload-select-bottom {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    display: flex;
    background: rgba(0, 0, 0, 0.6);
    line-height: 1.5;
    color: var(--color-white);
    justify-content: space-around;
    border-bottom-left-radius: var(--base-radius-small);
    border-bottom-right-radius: var(--base-radius-small);
    opacity: 0;
    transition: all 0.3s;
}

.upload-select-btn:hover .upload-select-bottom {
    opacity: 1;
}

.upload-select-btn:hover .upload-select-del {
    opacity: 1;
}
</style>