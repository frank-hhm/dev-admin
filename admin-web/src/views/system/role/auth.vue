<template>
    <div>
        <a-drawer class="drawer-default" title="授权设置" v-model:visible="visible" :width="isMobile?'calc(100% - 20px)':'500px'">
            <template #default>
                <div v-loading="initLoading">
                    <a-tree :data="ruleList" ref="treeRef" :checkable="true" v-model:selected-keys="checkedKeys"
                        v-model:checked-keys="checkedKeys" :check-strictly="true" :fieldNames="{
                            key: 'id',
                            title: 'label',
                            children: 'children'
                        }" multiple>
                    </a-tree>
                </div>
            </template>
            <template #footer>
                <a-space>
                    <a-button @click="close">取消</a-button>
                    <a-button @click="onSave()" type="primary" :loading="btnLoading" :disabled="initLoading || btnLoading">确定</a-button>
                </a-space>
            </template>
        </a-drawer>
    </div>
</template>
<script lang="ts">
export default {
    name: "systemRoleAuth",
};
</script>
<script lang="ts" setup>
import { onMounted, ref, getCurrentInstance, nextTick } from "vue";
import { getRoleRule } from "@/api/system/role";
import { useRouter } from "vue-router";
import { saveRules, getDetailRoleApi } from "@/api/system/role";
import { Result, ResultError } from '@/types';
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store";

const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const emit = defineEmits(["success"]);

const treeRef = ref<HTMLElement>();

interface RuleItemType {
    id: number;
    label: string;
    children: RuleItemType[];
}

const ruleList = ref<RuleItemType[]>([]);

const initLoading = ref<boolean>(true)

const checkedKeys = ref<number[] | string[]>([])

const toInit = () => {
    initLoading.value = true
    getRoleRule()
        .then((res) => {
            ruleList.value = res.data;
            nextTick(() => {
                proxy?.$refs['treeRef']?.expandAll();
            })
        })
        .catch((error) => { });
    getDetailRoleApi({ id: roleId.value })
        .then(res => {
            checkedKeys.value = checkRulesInt(res.data.rules);
            initLoading.value = false
        })
        .catch(res => {
            $utils.errorMsg(res);
        });
};

const checkRulesInt = (rules: string[] | number[]) => {
    let newRules: number[] = [];
    rules.forEach((item: string | number) => {
        newRules.push(Number(item))
    })
    return newRules;
}

const btnLoading = ref<boolean>(false)

const onSave = () => {
    if (btnLoading.value) {
        return
    }
    btnLoading.value = true
    saveRules({
        id: roleId.value,
        rules: checkedKeys.value,
    })
        .then((res) => {
            $utils.successMsg(res.message);
            emit("success");
            btnLoading.value = false
            close()
        })
        .catch((error) => {
            $utils.errorMsg(error);
            btnLoading.value = false
        });
};

const roleId = ref<number>(0);

const visible = ref<boolean>(false);

const open = (id: number = 0) => {
    roleId.value = id;
    nextTick(() => {
        toInit();
    });
    visible.value = true;
};

const close = () => {
    roleId.value = 0;
    checkedKeys.value = [];
    visible.value = false;
};

defineExpose({ open });
</script>