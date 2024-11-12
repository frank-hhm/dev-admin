<template>
    <a-modal v-model:visible="visible" title="上传素材" :width="isMobile ? 'calc(100% - 20px)' : '800px'" :top="useSetting().ModalTop" @BeforeCancel="close"
        :align-center="false" :mask-closable="false">
        <a-upload ref="uploadRef" draggable multiple :auto-upload="false" @success="uploadSuccess" @error="uploadError"
            @change="uploadChange" v-model:file-list="uploadMediaLists" :show-file-list="false" :accept="uploadAccept"
            :data="{}">
            <template #upload-button>
                <div class="meida-upload-box">
                    <div class="fz12 text-grey">将文件拖拽到此处上传 或者，您可以单击下方链接选择一个本地文件</div>
                    <div class="mt10 text-default">
                        <icon-upload />点击选择
                    </div>
                    <div class="mt10 fz12 text-grey">支持文件后缀：{{ uploadAccept }}</div>
                </div>
            </template>
        </a-upload>
        <div :class="isMobile ? 'table-mobile' : ''">
            <template v-if="mediaFormData.length > 0">
                <a-table class="mt20" :data="mediaFormData" :scroll="{
        maxHeight: '300px'
    }" :pagination="false">
                    <template #columns>
                        <a-table-column title="名称" :width="isMobile ? 80 : undefined">
                            <template #cell="{ record }">
                                <div class="text-grey table-file-name">{{ record.name }} </div>
                            </template>
                        </a-table-column>
                        <a-table-column title="" align="center" :width="isMobile ? 30 : 120">
                            <template #cell="{ record }">
                                <template v-if="record.status == 2 || record.status == 3">
                                    <a-progress size="mini" :percent="record.progress.percent / 100" />
                                </template>
                            </template>
                        </a-table-column>
                        <a-table-column title="状态" align="center" :width="isMobile ? 80 : 120">
                            <template #cell="{ record }">
                                <template v-if="record.status == 1">
                                    <span class="fz12 text-grey">待上传</span>
                                </template>
                                <template v-else-if="record.status == 2 || record.status == 3">
                                    <span class="fz12 text-grey">正在上传</span>
                                </template>
                                <template v-else-if="record.status == 4
        ">
                                    <span class="fz12 text-green">上传成功</span>
                                </template>
                                <template v-else>
                                    <div class="flex items-center">
                                        <span class="fz12 text-red">上传失败</span>
                                        <template v-if="record.errorMsg != ''">
                                            <a-tooltip placement="top">
                                                <template #content>
                                                    {{ record.errorMsg }}
                                                </template>
                                                <span class="text-grey ml5 fz12 icon icon-bangzhu"></span>
                                            </a-tooltip>
                                        </template>
                                    </div>
                                </template>
                            </template>
                        </a-table-column>
                        <a-table-column title="操作" align="right" :width="isMobile ? undefined : 120">
                            <template #cell="{ record, rowIndex }">
                                <template v-if="record.status == 5">
                                    <a-button v-btn link type="text" @click="onOneUpload(record)"
                                        size="mini">重新上传</a-button>
                                </template>
                                <template v-if="record.status == 1">
                                    <a-button v-btn link type="text" @click="onDelete(record, rowIndex)"
                                        size="mini">移除</a-button>
                                </template>
                            </template>
                        </a-table-column>
                    </template>
                </a-table>
            </template>
        </div>
        <template #footer>
            <a-space>
                <a-button v-btn @click="close()">关闭</a-button>
                <a-button v-btn type="primary" :disabled="btnLoading" :loading="btnLoading"
                    @click="onUpload()">上传</a-button>
            </a-space>
        </template>
    </a-modal>
</template>
<script lang="ts">
export default {
    name: "mediaUpload",
};
</script>
<script lang="ts" setup>
import { getCurrentInstance, onMounted, ref } from "vue";
import { Result, ResultError } from "@/types";
import axios from 'axios';
import { getToken } from '@/utils';
import { uploadMediaApi } from '@/api/media';
import { Modal, type FileItem } from '@arco-design/web-vue';
import { useSetting } from "@/hooks/useSetting";
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";

const { isMobile } = storeToRefs(useAppStore());
const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const visible = ref(false);

const apiUrl = ref<string>($utils.baseApiUrl() + "sys/media/upload")

const uploadRef = ref<HTMLElement>()

const uploadAccept = ref<string>(".gif,.jpg,.jpeg,.bmp,.png,.mp4,.mp3");

const uploadLoading = ref<boolean>(false)

const uploadMediaLists = ref<any>([]);

const mediaFormData = ref<any>([])

const uploadSuccess = () => {
    return false;
};

const uploadError = () => { };

const uploadChange = (res: FileItem[]) => {
    res.forEach((item: any) => {
        let index = $utils.arrayFilterMultiple(mediaFormData.value, 'name', item.name)
        if (index === -1) {
            mediaFormData.value.push({
                file: item.file,
                name: item.name,
                status: 1,
                progress: {
                    percent: 0
                }
            })
        }
    })

}

const btnLoading = ref<boolean>(false)

const uploadCount = ref<number>(0)

const onUpload = async () => {
    if (mediaFormData.value.length) {
        uploadLoading.value = true;
        uploadCount.value = 0;
        const currentDate = new Date();  // 创建一个Date对象，表示当前时间
        mediaFormData.value.forEach((item: any, index: number) => {
            if (item.status == 1) {
                item.status = 2
                onUploadApi(item, (res: Result) => {
                    uploadLoading.value = false;
                    $utils.successMsg("上传完成")
                })
            } else {
                uploadCount.value++
            }
        }) // 执行所有请求
        await Promise.all(promises.value);
    } else {
        $utils.errorMsg("请选择素材");
    }
}

//并发
const maxRequestCount = ref<number>(5);
const promises = ref<any>([]);
let currentRequestCount = ref<number>(0);

const onUploadApi = async (item: any, cell: Function) => {
    // 判断当前并发请求数量是否超过最大值
    if (currentRequestCount.value < maxRequestCount.value) {
        promises.value.push(toAxios(item, cell));
        currentRequestCount.value++;
    } else {
        await Promise.race(promises.value);
        promises.value.shift();
        promises.value.push(toAxios(item, cell));
    }
}

const eventController = ref<any>(null)

const toAxios = (item: any, cell: Function) => {
    let fd = new FormData();
    fd.append('file', item.file);
    if (groupId.value != -1) {
        fd.append('group_id', String(groupId.value));
    }
    eventController.value = new AbortController();
    const { progress, request } = uploadMediaApi(fd, {
        isAllow: true,
        signal: eventController.value.signal
    })
    item.progress = progress
    item.status = 3
    request.then((res: Result) => {
        item.status = 4;
        item.progress = {
            parent: 100
        }
        cell(item)
    }).catch((err: ResultError) => {
        item.status = 5;
        item.errorMsg = err.data?.message;
        uploadCount.value++
    });
}


//单个重新上传
const onOneUpload = (uploadFile: any) => {
    uploadFile.status = 3
    uploadFile.progress = {
        parent: 0
    }
    uploadLoading.value = true;
    toAxios(uploadFile, () => {
        uploadLoading.value = false;
        $utils.successMsg("上传完成")
    });
}

const onDelete = (uploadFile: FileItem, index: number) => {
    mediaFormData.value.splice(index, 1)
    uploadMediaLists.value.splice(index, 1)
}

const getFilsUploadCount = () => {
    let status: boolean = true
    mediaFormData.value.forEach((item: any, index: number) => {
        if (item.status != 4) {
            status = false
        }
    })
    return status;
}

const groupId = ref<number>(-1)

const open = (id: number = -1) => {
    groupId.value = id
    visible.value = true;
};

const close = () => {
    visible.value = false;
    if (!getFilsUploadCount()) {
        Modal.confirm({
            title: '提示',
            content: '您还有未上传的文件，是否放弃上传？',
            onOk: () => {
                if (typeof eventController.value?.abort == 'function') {
                    eventController.value.abort();
                }
                visible.value = false;
                return true;
            },
            onCancel: () => {
                return true;
            }
        });
    }
    return true;
};

onMounted(() => {
});

defineExpose({ open, close });

</script>

<style scoped>
.meida-upload-box {
    width: 100%;
    padding: 50px 0;
    color: var(--color-text-1);
    text-align: center;
    background-color: var(--color-fill-1);
    border: 1px dashed var(--color-border-1);
    border-radius: var(--border-radius-small);
    transition: all .2s ease;
}
.table-mobile .table-file-name {
    width: 50px;
    white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis; 
}
</style>