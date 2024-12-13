<template>
    <div class="media-group-box" :class="[isModal ? 'is-modal' : '']">
        <div class="media-group-box-head">
            <div v-if="!isModal">分组</div>
            <a-button size="small" @click="onCreate()" v-permission="'media-group-create'">新增分组</a-button>
        </div>
        <div class="media-group-list" v-loading="groupLoading">
            <div class="media-group-item" :class="-1 == selectedGroupId ? 'is-active' : ''"
                @click="onSelectedGroup(-1, '全部')">
                <div class="media-group-name">
                    <icon-folder />
                    <div :class="isModal ? 'ml5' : 'ml10'">全部</div>
                </div>
            </div>
            <div class="media-group-item" :class="0 == selectedGroupId ? 'is-active' : ''"
                @click="onSelectedGroup(0, '未分组')">
                <div class="media-group-name">
                    <icon-folder />
                    <div :class="isModal ? 'ml5' : 'ml10'">未分组</div>
                </div>
            </div>
            <template v-for="(item, index) in groupList" :key="index">
                <div class="media-group-item" :class="item.id == selectedGroupId ? 'is-active' : ''"
                    @click.stop="onSelectedGroup(item.id, item.group_name)">
                    <div class="media-group-name">
                        <div v-if="item.pid > 0" class="text-grey">
                            {{ item.html }}
                        </div>
                        <div style="width:12px;">
                            <icon-folder />
                        </div>
                        <a-tooltip :content="item.group_name">
                            <div :class="isModal ? 'ml5' : 'ml10'" class="media-group-name-text">
                                {{ item.group_name }}
                            </div>
                        </a-tooltip>
                    </div>
                    <div class="media-group-item-action">
                        <icon-edit v-if="isModal" v-permission="'media_group-update'"
                            @click.stop="onCreate(item.id, item.group_name, item.pid)" />
                        <a-button type="text" v-else v-permission="'media_group-update'"
                            @click.stop="onCreate(item.id, item.group_name, item.pid)" size="mini">编辑</a-button>
                        <a-popconfirm content="确定删除吗？" @ok.stop="onDelete(item.id)">
                            <icon-delete v-if="isModal" class="ml10" />
                            <a-button type="text" size="mini" v-permission="'media_group-delete'" v-else>删除</a-button>
                        </a-popconfirm>
                    </div>
                </div>
            </template>
        </div>
        <!-- 添加 -->
        <mediaGroupCreate ref="createComponentRef" @success="toInit()"></mediaGroupCreate>
    </div>
</template>
<script lang="ts">
export default {
    name: "mediaGroup",
};
</script>
<script lang="ts" setup>
import { ref, reactive, onMounted, getCurrentInstance } from "vue";
import mediaGroupCreate from "./group-create.vue";
import { getMediaGroupListApi, deleteMediaGroupApi } from "@/api/media";
import type { Result, ResultError } from "@/types"
import { setgroups } from "process";
const { proxy } = getCurrentInstance() as any
const {
    proxy: { $utils },
} = getCurrentInstance() as any;

const props = withDefaults(
    defineProps<{
        isModal?: boolean;
        width?: string;
        type?: string;
    }>(),
    {
        isModal: false,
        width: "386px",
        type: "image"
    }
);

const mediaType = ref<string>(props.type);

const selectedGroupId = ref<number | string>(-1);

const emit = defineEmits(["change", "initChange"]);

const createComponentRef = ref<HTMLElement>();

const groupList = ref<any>([]);

const groupLoading = ref<boolean>(false);

const toInit = () => {
    groupLoading.value = true;
    getMediaGroupListApi({
        type: mediaType.value,
    })
        .then((res: Result) => {
            groupList.value = res.data;
            emit('initChange', groupList.value);
            setTimeout(() => {
                groupLoading.value = false;
            }, 100);
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err)
        });
};

const onCreate = (id: number | string = 0, group_name: string = '', pid: number | string | undefined | unknown = '') => {
    proxy?.$refs['createComponentRef']?.open(mediaType.value, id, group_name, pid);
}


const onDelete = (id: number) => {
    deleteMediaGroupApi({
        group_id: id,
    })
        .then((res: Result) => {
            emit("change", { group_id: -1, group_name: '全部' })
            toInit();
            $utils.successMsg(res.message);
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};

const onSelectedGroup = (val: number | string, title: string) => {
    selectedGroupId.value = val
    emit("change", { group_id: val, group_name: title })
}

const toTypeInit = (type: string) => {
    mediaType.value = type;
    toInit();
}

onMounted(() => {
    toInit();
});
defineExpose({ toTypeInit });
</script>
<style scoped>
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: var(--color-fill-1);
}

::-webkit-scrollbar-thumb {
    background-color: var(--color-fill-3);
}

.media-group-box {
    flex: 0 0 386px;
    width: 386px;
    height: 100%;
    border-right: 1px solid var(--color-border-1);
    border-radius: 2px;
    color: var(--color-text-1);
}

.media-group-box.is-modal {
    flex: 0 0 160px;
    width: 160px;
    height: 100%;
}

.media-group-box-head {
    padding: 0 20px;
    height: 45px;
    text-align: left;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid var(--color-border-1);
    align-items: center;
    color: var(--color-text-1);
}

.media-group-list {
    overflow: auto;
    position: relative;
    height: calc(100% - 46px);
}

.media-group-item {
    padding: 0 10px;
    text-align: left;
    position: relative;
    height: 48px;
    line-height: 48px;
    width: calc(100% - 20px);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    user-select: none;
}

.media-group-name {
    display: flex;
    align-items: center;
    width: calc(100% - 120px);
    min-width: 40px;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}

.media-group-name .media-group-name-text {
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    word-break: break-all;
}

.media-group-box.is-modal .media-group-name {
    width: calc(100% - 50px);
}

.media-group-item:hover,
.media-group-item.is-active {
    color: rgba(var(--primary-6));
}

.media-group-item-action {
    display: none;
    align-items: center;
}

.media-group-item:hover .media-group-item-action,
.media-group-item.is-active .media-group-item-action {
    display: flex;
    color: rgba(var(--primary-6));
}

.media-group-item.is-active {
    background: var(--color-fill-2);
}
</style>