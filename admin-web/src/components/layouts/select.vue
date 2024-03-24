<template>
    <div>
        <a-drawer class="drawer-default" title="主题设置" v-model:visible="visible" :width="300" :footer="false">
            <template #default>
                <div>
                    <div>风格设置</div>
                    <div class="layout-list">
                        <div class="layout-item" :class="[_layout == 'default'?'active':'']" @click="onSetLayout('default')">默认风格</div>
                        <div class="layout-item" :class="[_layout == 'layout1'?'active':'']" @click="onSetLayout('layout1')">风格一</div>
                    </div>
                </div>
                <div class="mt20 flex between">
                    <div>开启黑暗模式</div>
                    <a-switch type="round" :default-checked="isDark" @change="onTemplate" />
                </div>
            </template>
        </a-drawer>
    </div>
</template>
<script lang="ts">
export default {
    name: "select-layout",
};
</script>
<script lang="ts" setup>
import { onMounted, ref, getCurrentInstance, nextTick } from "vue";
import { useAppStore } from "@/store";
import { storeToRefs } from "pinia";

const { isDark,layout } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const emit = defineEmits(["success"]);

const _layout = ref<string>(layout.value)

const onTemplate = () => {
  useAppStore().setDark(!isDark.value)
}

const onSetLayout = (val:string) => {
  useAppStore().setLayout(val)
  _layout.value = val
}

const visible = ref<boolean>(false);

const open = () => {
    visible.value = true;
};

const close = () => {
    visible.value = false;
};

defineExpose({ open });
</script>

<style scoped>
.layout-list{
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
}
.layout-item{
    width:calc( 50% - 20px);
    height:40px;
    line-height:40px;
    background:var(--color-neutral-3);
    margin-right: 20px;
    border-radius: var(--base-radius);
    text-align: center;
    cursor: pointer;
    user-select: none;
}
.layout-item.active{
    color:rgba(var(--primary-6));
    background:rgba(var(--primary-1))
}
</style>