<template>
    <div>
        <layout-body>
            <a-button type="primary" @click="onCreate(0)" v-permission="'system-admin-create'">添加部门</a-button>

            <systemAdminCreate ref="createComponentRef" @success="toInit(true)"></systemAdminCreate>
        </layout-body>
    </div>
</template>
<script lang="ts" setup>
import {
    onMounted,
    ref,
    getCurrentInstance,
} from "vue";

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const toInit = (isInit: boolean = false) => {


}

const onCreate = (id: number | string) => {
    proxy?.$refs["createComponentRef"]?.open(id, 1);
};

</script>