<template>
    <div>
        <a-modal :title="title" :width="isMobile ? 'calc(100% - 20px)' : '808px'" :top="useSetting().ModalTop" class="modal" v-model:visible="visible"
            :align-center="false" title-align="start" @BeforeCancel="close">
            <div class="cropper-body" :class="isMobile ? 'mobile' : ''" v-if="visible">
                <a-upload v-if="!selectFileItem" @change="uploadChange" :auto-upload="false" draggable
                    :show-file-list="false" :accept="accept">
                    <template #upload-button>
                        <div class="upload-box">
                            <div class="fz12 pt100 text-grey">将文件拖拽到此处上传 或者，您可以单击下方链接选择一个本地文件</div>
                            <div class="mt10 text-default">
                                <icon-upload />点击选择
                            </div>
                            <div class="mt10 fz12 text-grey">支持文件后缀：{{ accept }}</div>
                        </div>
                    </template>
                </a-upload>
                <div v-if="selectFileItem" class="cropper-mian">
                    <div class="cropper-box">
                        <VueCropper ref="cropperRef" :img="selectFileSrc" :outputSize="outputSize"
                            :outputType="outputType" :info="info" :canScale="canScale" :autoCrop="autoCrop"
                            :autoCropWidth="autoCropWidth" :autoCropHeight="autoCropHeight" :fixedBox="fixedBox"
                            :fixed="fixed" :fixedNumber="fixedNumber" :canMove="canMove" :canMoveBox="canMoveBox"
                            :original="original" :centerBox="centerBox" :infoTrue="infoTrue" :full="full"
                            :enlarge="enlarge" :mode="mode" style="width: 100%; height: 100%;"
                            @realTime="onChangeRealTime">
                        </VueCropper>
                    </div>
                    <div class="cropper-cover-box">
                        <div class="cropper-cover" v-if="previewCover?.html" v-html="previewCover.html">
                        </div>
                    </div>
                </div>
            </div>
            <template #footer>
                <div class="cropper-footer" :class="isMobile ? 'mobile' : ''">
                    <a-space wrap>
                        <template v-if="visible && selectFileItem">
                            <a-upload @change="uploadChange" :show-file-list="false" :auto-upload="false"
                                :accept="accept">
                                <template #upload-button>
                                    <a-button>重新上传</a-button>
                                </template>
                            </a-upload>
                            <a-button @click="onChangeScale(1)">
                                <icon-plus-circle />
                            </a-button>
                            <a-button @click="onChangeScale(-1)">
                                <icon-minus-circle />
                            </a-button>
                            <a-button @click="onRotateLeft">
                                <icon-undo />
                            </a-button>
                            <a-button @click="onRotateRight">
                                <icon-redo />
                            </a-button>
                        </template>
                        <a-button v-if="visible && selectFileItem" type="primary" @click="onOk">生成</a-button>
                        <a-button @click="close">关闭</a-button>
                    </a-space>
                </div>
            </template>
        </a-modal>
    </div>
</template>

<script lang="ts">
import 'vue-cropper/dist/index.css'

import { VueCropper } from "vue-cropper";
export default defineComponent({
    name: "img-cropper",
    components: {
        VueCropper,
    }
});
</script>
<script lang="ts" setup>
import { defineComponent, getCurrentInstance, nextTick, ref } from "vue";
import { FileItem } from "@arco-design/web-vue";
import { useSetting } from "@/hooks/useSetting";

import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";

const { isMobile } = storeToRefs(useAppStore());

const props = withDefaults(
    defineProps<{
        title?: string;
        img?: string;
        accept?: string;
        outputSize?: number;
        outputType?: string;
        info?: boolean;
        canScale?: boolean;
        autoCrop?: boolean;
        autoCropWidth?: number | string;
        autoCropHeight?: number | string;
        fixedBox?: boolean;
        fixed?: boolean;
        fixedNumber?: number[] | any[] | any;
        canMove?: boolean;
        canMoveBox?: boolean;
        original?: boolean;
        centerBox?: boolean;
        infoTrue?: boolean;
        full?: boolean;
        enlarge?: string;
        mode?: string;
        autoClose?: boolean;

    }>(),
    {
        title: "图片截剪",
        img: "http://dev-admin.dev-frank.cn/uploads/storage/media/657d60140b17f.png",
        accept: ".jpg,.jpeg,.png",
        outputSize: 1, // 裁剪生成图片的质量
        outputType: 'jpeg', // 裁剪生成图片的格式 jpeg, png, webp
        info: true, // 裁剪框的大小信息
        canScale: false, // 图片是否允许滚轮缩放
        autoCrop: true, // 是否默认生成截图框
        autoCropWidth: 150, // 默认生成截图框宽度
        autoCropHeight: 150, // 默认生成截图框高度
        fixedBox: false, // 固定截图框大小 不允许改变
        fixed: true, // 是否开启截图框宽高固定比例，这个如果设置为true，截图框会是固定比例缩放的，如果设置为false，则截图框的狂宽高比例就不固定了
        fixedNumber: [1, 1], // 截图框的宽高比例 [ 宽度 , 高度 ]
        canMove: true, // 上传图片是否可以移动
        canMoveBox: true, // 截图框能否拖动
        original: false, // 上传图片按照原始比例渲染
        centerBox: true, // 截图框是否被限制在图片里面
        infoTrue: false, // true 为展示真实输出图片宽高 false 展示看到的截图框宽高
        full: true, // 是否输出原图比例的截图
        enlarge: '1', // 图片根据截图框输出比例倍数
        mode: 'contain', // 图片默认渲染方式 contain , cover, 100px, 100% auto
        autoClose: true
    }
);

const emit = defineEmits(["change"]);

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const cropperRef = ref<HTMLElement>()

const selectFileItem = ref<FileItem | null>(null)

const selectFileSrc = ref<string | undefined>('')

const uploadChange = (res: FileItem[]) => {
    selectFileItem.value = res[0]
    selectFileSrc.value = selectFileItem.value.url
}

const previewCover = ref<any>(null)

const previewCoverStyle = ref<any>(null)

const onChangeRealTime = (data: any) => {
    previewCover.value = data

}

const onChangeScale = (type: number) => {
    proxy?.$refs['cropperRef']?.changeScale(type || 1)
}

const onRotateLeft = () => {
    proxy?.$refs['cropperRef']?.rotateLeft()
}

const onRotateRight = () => {
    proxy?.$refs['cropperRef']?.rotateRight()
}

// base64转图片文件
const dataURLtoFile = (dataUrl: string, filename: string) => {
    const arr: any = dataUrl.split(',')
    const mime = arr[0].match(/:(.*?);/)[1]
    const bstr = atob(arr[1])
    let len = bstr.length
    const u8arr = new Uint8Array(len)
    while (len--) {
        u8arr[len] = bstr.charCodeAt(len)
    }
    return new File([u8arr], filename, { type: mime })
}

const onOk = () => {
    proxy?.$refs['cropperRef']?.getCropData(async (data: string) => {
        const dataFile: File = dataURLtoFile(data, 'images.' + props.outputType)
        // 触发自定义事件
        emit('change', dataFile)
        if (props.autoClose) {
            close();
        }
    })
}

const visible = ref<boolean>(false);

const open = () => {
    visible.value = true;
    // nextTick(() => {
    proxy?.$refs['cropperRef']?.reset()
    // })
};

const close = () => {
    selectFileItem.value = null
    visible.value = false;
    return true
};

defineExpose({ open, close });
</script>

<style>
.cropper-crop-box .crop-point{
    background-color: rgb(var(--primary-6)) !important;
}
.cropper-crop-box .cropper-view-box{
    outline-color: rgb(var(--primary-6),0.8) !important;

}</style>
<style scoped>
.cropper-body {
    height: 300px;
    width: 782px;
}

.cropper-mian {
    height: 300px;
    width: 780px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cropper-box {
    height: 300px;
    width: 380px;
}

.cropper-cover-box {
    width: 380px;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--color-fill-1);
    border: 1px dashed var(--color-border-1);
    border-radius: var(--border-radius-small);
}
.cropper-footer{
    display: block;
    justify-content: space-between;
}
.cropper-footer>div{
    /* margin-bottom: 10px; */
}
.cropper-body.mobile{
    width: calc(100%);
}
.cropper-body.mobile .cropper-mian{
    width: calc(100%);
    display: block;
}
.cropper-body.mobile .cropper-box{
    width: calc(100%);
}
.cropper-body.mobile .cropper-cover-box{
    margin-top:20px;
    width: calc(100%);
}
.cropper-footer.mobile{
    display: flex;
    flex-wrap:wrap;
}
.cropper-cover {
    width: calc(100% - 20px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-box {
    width: 100%;
    height: 300px;
    padding: 0;
    color: var(--color-text-1);
    text-align: center;
    background-color: var(--color-fill-1);
    border: 1px dashed var(--color-border-1);
    border-radius: var(--border-radius-small);
    transition: all .2s ease;
}
</style>