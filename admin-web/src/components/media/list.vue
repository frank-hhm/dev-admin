<template>
    <div class="media-body">
        <mediaGroup @change="changeGroup" @initChange="initGroup" :width="groupWidth + 'px'" :isModal="isModal">
        </mediaGroup>
        <div v-loading="initLoading" class="media-box"
            :style="[`min-width:calc(100% - 1px - ` + groupWidth + `px)`]">
            <div class="media-select-group-name">
                <div>{{ activeGroupName }}</div>
                <div class="flex">
                    <a-button class="mr15" size="small" v-permission="'media-delete'" @click="onDeletes()">删除</a-button>
                    <div v-permission="'media-update-group'" v-if="groupList.length > 0">
                        <a-dropdown trigger="click" @select="onSetGroup">
                            <a-button class="mr15" size="small">移动到分组</a-button>
                            <template #content>
                                <a-doption v-if="groupList.length == 0" disabled>无</a-doption>
                                <template v-else v-for="(item, index) in groupList" :key="index">
                                    <a-doption :value="item.id">{{ item.group_name }}</a-doption>
                                </template>
                            </template>
                        </a-dropdown>
                    </div>
                    <a-button type="primary" v-permission="'common-upload-image'" @click="onUpload"
                        size="small">上传素材</a-button>
                    <mediaUpload ref="mediaUploadRef" v-if="mediaUploadVisible" @close="onUploadClose"></mediaUpload>
                </div>
            </div>
            <div class="media-list-box" ref="mediaRef" :class="isModal ? 'is-modal' : ''">
                <div class="media-list">
                    <template v-for="(item, index) in lists" :key="index">
                        <div class="media-item" @click.capture="onSelectedItem(item)">

                            <template v-if="item.file_type == 'image'">
                                <div class="img-cover">
                                    <a-image show-loader width="100%" height="100%" :src="item.file_url" fit="cover"
                                        v-if="!isModal" />
                                    <a-image show-loader :preview="false" width="100%" height="100%" :src="item.file_url"
                                        fit="cover" v-else />
                                </div>
                            </template>
                            <template v-else-if="item.file_type == 'video'">
                                <div class="media-item-video icon icon-shipin "></div>
                            </template>
                            <div class="select-mask" v-if="isModal" v-show="item.active">
                                <div class="mask-icon">
                                    <i class="icon icon-gouxuan"></i>
                                </div>
                            </div>
                            <div class="media-item-tool">
                                <a-checkbox v-model="item.check" v-if="!isModal"></a-checkbox>
                                <div class="media-name ml5">{{ item.former_name }}</div>
                                <a-dropdown trigger="hover">
                                    <span class="icon icon-gengduo-2"></span>
                                    <template #content>
                                        <a-doption @click="onCopy(item.file_url)">复制链接</a-doption>
                                        <div v-permission="'media-update-name'">
                                            <a-doption @click="onEditFileName(item)">改素材名</a-doption>
                                        </div>
                                        <div v-permission="'media-delete'">
                                            <a-doption @click="onDelete(item.id)">删除</a-doption>
                                        </div>
                                    </template>
                                </a-dropdown>
                            </div>
                        </div>
                    </template>
                    <a-empty v-if="!initLoading && lists.length == 0" />
                </div>
                <div class="media-right-box" v-if="isModal && type == 'image'">
                    <div class="media-right-box-list">
                        <template v-for="(item, index) in selectedDataList" :key="index">
                            <div class="media-item" draggable="true" @dragstart="dragstart(item)"
                                @dragenter="dragenter(item, $event)" @dragend="dragend(item, $event)"
                                @dragover="dragover($event)" @click="delItem(item, index)">
                                <div class="img-cover">
                                    <a-image show-loader :preview="false" width="100%" height="100%" class="img-cover"
                                        :src="item" fit="cover" />
                                </div>
                                <div class="yes-icon icon icon-gouxuan"></div>
                            </div>
                        </template>
                    </div>
                    <div class="media-select-count">
                        已选择{{ selectedDataList.length }}<template v-if="count != -1">/{{ count }}</template>张
                    </div>
                </div>
            </div>

            <!-- 分页 -->
            <div class="media-page">
                <a-pagination v-model:current="listPage.page" v-model:page-size="listPage.limit" show-total
                    :total="listPage.total" size="mini" @change="changeCurPage" />
            </div>
        </div>
        <mediaUpdateName ref="mediaUpdateNameRef" @success="toInit()"></mediaUpdateName>
    </div>
</template>
<script lang="ts">
export default {
    name: "media",
};
</script>
<script lang="ts" setup>
import { onMounted, ref, getCurrentInstance, nextTick } from "vue";
import mediaGroup from "@/components/media/group.vue";
import mediaUpdateName from "@/components/media/update-name.vue";
import mediaUpload from "@/components/media/upload.vue";
import { getMediaListApi, setMediaGroupApi, deleteMediaApi } from "@/api/media";
import router from "@/router/index";
import type { PageLimitType, Result, ResultError } from "@/types";
import { Modal } from '@arco-design/web-vue';


const { proxy } = getCurrentInstance() as any;
const {
    proxy: { $message, $utils },
} = getCurrentInstance() as any;
const props = withDefaults(
    defineProps<{
        isModal?: boolean;
        groupWidth?: string | number;
        count?: number | string;
        type?: string;
    }>(),
    {
        isModal: false,
        groupWidth: 386,
        count: 1,
        type: 'image'
    }
);

const emit = defineEmits(["change"]);

const activeGroupId = ref<number | string>(-1);

const activeGroupName = ref<string>("全部");

const changeGroup = (obj: {
    group_id: number | string;
    group_name: string;
}) => {
    activeGroupId.value = obj.group_id;
    activeGroupName.value = obj.group_name;
    listPage.page = 1;
    toInit();
};

const initLoading = ref<boolean>(true);

const lists = ref<any>([]);

const selectedIds = ref<any>([]);

const selectedDataList = ref<string | number | string[] | number[] | any>([]);

const listPage: PageLimitType = {
    total: 0,
    limit: 0,
    page: 1,
};

const changeCurPage = (val: number) => {
    console.log(val);
    listPage.page = val;
    toInit();
};

const groupList = ref<any>([]);

const initGroup = (data: any) => {
    groupList.value = data;
};

const toInit = () => {
    initLoading.value = true;
    let obj: any = {};
    obj.page = listPage.page;
    obj.limit = listPage.limit;
    obj.group_id = activeGroupId.value;
    obj.type = props.type
    getMediaListApi(obj)
        .then((res: Result) => {
            lists.value = res.data.data;
            lists.value.forEach((item: any) => {
                item.check = false;
                item.active = false;
            });
            listPage.total = res.data.total;
            initActive();
            setTimeout(() => {
                initLoading.value = false;
            }, 500);
        })
        .catch((err: ResultError) => {
            setTimeout(() => {
                initLoading.value = false;
            }, 500);
            $utils.errorMsg(err)
        });
};

const initActive = () => {
    lists.value.forEach((item: any) => {
        if (
            $utils.arrayFilterMultiple(
                selectedDataList.value,
                false,
                item.file_url
            ) !== -1
        ) {
            item.active = true;
        }
    });
};

const mediaUploadRef = ref<HTMLElement>()

const onUpload = () => {
    proxy?.$refs["mediaUploadRef"]?.open(activeGroupId.value)
}

const mediaUploadVisible = ref<boolean>(true)

const onUploadClose = () => {
    mediaUploadVisible.value = false
    toInit()
    nextTick(() => {
        mediaUploadVisible.value = true
    })
}

const onCopy = (fileUrl: string) => {
    $utils.copyDomText(fileUrl);
};

const onDeletes = () => {
    onDelete(getIds());
};

const getIds = () => {
    if (props.isModal) {
        selectedIds.value = [];
        lists.value.forEach((item: { file_url: string; id: number }) => {
            let index = $utils.arrayFilterMultiple(
                selectedDataList.value,
                false,
                item.file_url
            );
            if (index !== -1) {
                selectedIds.value.push(item.id);
            }
        });
        return selectedIds.value.toString();
    } else {
        return $utils
            .getArrayDataByKey(
                $utils.getArrayColumnsByKeyValue(lists.value, "check", true),
                "id"
            )
            .toString();
    }
};

const onDelete = (field: number | string) => {
    if (!field) {
        $utils.errorMsg("请选择素材");
        return false;
    }
    Modal.confirm({
        title: "提示",
        content: "确定删除?",
        onOk() {
            deleteMediaApi({
                ids: field,
            })
                .then((res: Result) => {
                    toInit();
                    $utils.successMsg(res.message);
                })
                .catch((err: ResultError) => {
                    $utils.errorMsg(err);
                });
        }
    })
};

const onSetGroup = (command: string | number | any) => {
    console.log(command)
    let ids = getIds();
    if (!ids) {
        $utils.errorMsg("请选择素材");
        return false;
    }
    setMediaGroupApi({
        ids: ids,
        group_id: command,
    })
        .then((res: Result) => {
            toInit();
            $utils.successMsg(res.message);
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};

const onSelectedItem = (item: any) => {
    if (!props.isModal) {
        return;
    }
    let index = $utils.arrayFilterMultiple(
        selectedDataList.value,
        false,
        item.file_url
    );
    if (props.count == 1) {
        selectedDataList.value = item.file_url;
        setYes();
    } else {
        if (index === -1) {
            selectedDataList.value.push(item.file_url);
            item.active = true;
        } else {
            selectedDataList.value.splice(index, 1);
            item.active = false;
        }
    }
};

const delItem = (url: string, index: number) => {
    selectedDataList.value.splice(index, 1);
    let idx = $utils.arrayFilterMultiple(lists.value, "file_url", url);
    if (idx !== -1) {
        lists.value[idx].active = false;
    }
};

const setYes = () => {
    emit("change", selectedDataList.value);
    selectedDataList.value = [];
};

const mediaUpdateNameRef = ref<HTMLElement>();

const onEditFileName = (item: { id: number; former_name: string }) => {
    proxy?.$refs["mediaUpdateNameRef"]?.open(item.id, item.former_name);
};

const mediaRef = ref<HTMLElement>();

const mediaWidth = ref<number>(0);

onMounted(() => {
    if (props.isModal) {
        listPage.limit = 21;
    } else {
        mediaWidth.value = proxy?.$refs["mediaRef"]?.offsetWidth - 10;
        listPage.limit =
            parseInt(Math.floor(mediaWidth.value / 144).toString()) * 3;
    }
});

const open = (selectedData: string | string[] = "") => {
    if (selectedData.length > 0) {
        if (typeof selectedData == "string") {
            selectedData = [selectedData];
        }
        selectedData.forEach((item: string) => {
            selectedDataList.value.push(item);
        });
    }
    toInit();
};

const close = () => {
    lists.value = [];
    listPage.page = 1;
    selectedDataList.value = [];
};

const dragoverOldItem = ref<any>();

const dragoverNewItem = ref<any>();

const dragstart = (value: any) => {
    dragoverOldItem.value = value;
};

// 停止拖拽时触发
const dragend = (value: any, e: any) => {
    // 如果位置没有发生改变 什么也不做
    if (dragoverOldItem.value !== dragoverNewItem.value) {
        let oldIndex = selectedDataList.value.indexOf(dragoverOldItem.value);
        let newIndex = selectedDataList.value.indexOf(dragoverNewItem.value);

        let newItems = [...selectedDataList.value];
        newItems.splice(oldIndex, 1);
        newItems.splice(newIndex, 0, dragoverOldItem.value);
        selectedDataList.value = [...newItems];
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

defineExpose({ open, close, setYes });
</script>
        
        
<style scoped>
.media-body {
    height: 100%;
    display: flex;
    justify-content: space-between;
    width: 100%;
    background: var(--color-bg-2);
}

.media-box {
    height: calc(100% - 0px);
    box-sizing: border-box;
    border-radius: 2px;
    font-size: 12px;
}

.media-select-group-name {
    padding: 0 20px;
    text-align: left;
    height: 45px;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid var(--color-border-1);
    align-items: center;
    color: var(--color-text-1);
}

.upload-btn {
    display: flex;
    align-items: center;
}

.media-list {
    padding: 10px 0 10px 10px;
    height: 452px;
    width: 100%;
}

.media-list-box.is-modal .media-list {
    width: calc(100% - 90px);
}

.media-list .media-item {
    position: relative;
    cursor: pointer;
    padding: 5px;
    border: 2px solid var(--color-border-1);
    float: left;
    width: 105px;
    height: 130px;
    margin: 0 10px 10px 0;
}

.media-list .media-item .img-cover {
    width: 100%;
    height: 110px;
}

.media-item-tool {
    display: flex;
    white-space: nowrap;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin-top: 5px;
    line-height: 16px;
    color:var(--color-text-1);
}

.media-list .media-item div.media-name {
    word-wrap: normal;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    font-size: 12px;
    width: calc(100% - 40px);
    color:var(--color-text-1);
}
.select-mask {
    position: absolute;
    top: -2px;
    bottom: -2px;
    width: 100%;
    height: 100%;
    left: -2px;
    right: -2px;
    background: rgba(0, 0, 0, 0.4);
    text-align: center;
    border: 2px solid rgba(var(--primary-6));
}

.mask-icon {
    position: absolute;
    left: calc(50% - 20px);
    top: calc(50% - 20px);
    width: 40px;
    height: 40px;
    background: rgba(var(--primary-6));
    border-radius: 40px;
    text-align: center;
    line-height: 40px;
    color: var(--color-white)
}

.mask-icon .icon {
    font-size: 30px;
}

.media-list-box {
    position: relative;
    display: flex;
    justify-content: space-between;
    height: calc(100% - 96px);
    border-bottom: 1px solid var(--color-border-1);
}

.media-list-box .media-right-box {
    height: 100%;
    width: 90px;
    border-left: 1px solid var(--color-border-1);
    text-align: center;
    position: relative;
}

.media-right-box-list {
    overflow: hidden;
    overflow-y: auto;
    position: absolute;
    height: calc(100% - 30px);
    top: 0;
    width: calc(100% - 6px);
    padding-right: 6px;
}

.media-right-box-list .media-item {
    position: relative;
    height: 74px;
    width: 74px;
    margin: 5px;
    border: 1px solid var(--color-border-1);
    cursor: pointer;
}

.media-right-box-list .media-item:hover {
    border: 1px solid rgba(var(--primary-6))
}

.media-right-box .media-select-count {
    position: absolute;
    width: 100%;
    bottom: 0;
    line-height: 30px;
    border-top: 1px solid var(--color-border-1);
    color: var(--color-text-3);
}

.media-right-box-list .media-item .img-cover {
    width: 74px;
    height: 74px;
}

.media-right-box-list .media-item .yes-icon {
    position: absolute;
    right: 0;
    top: 0;
    z-index: 1;
    line-height: 12px;
    color:var(--color-white);
    font-size: 12px;
}

.media-right-box-list .media-item .yes-icon::after {
    position: absolute;
    content: "";
    right: 0;
    top: 0;
    width: 0;
    height: 0;
    border-top: 20px solid rgba(var(--primary-6));
    border-left: 20px solid transparent;
    z-index: -1;
}

.media-page {
    display: flex;
    justify-content: end;
    padding: 10px 20px;
}

.media-item-video {
    width: 100%;
    height: 140px;
    text-align: center;
    line-height: 140px;
    font-size: 50px;
}
</style>
        