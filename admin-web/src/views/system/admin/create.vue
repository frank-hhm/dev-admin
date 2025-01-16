<template>
    <div>
        <a-modal :title="operation == 'create' ? '添加账号' : '编辑账号'" @BeforeOk="onCreateOk" @BeforeCancel="close"
            :width="isMobile ? 'calc(100% - 20px)' : '800px'" :top="useSetting().ModalTop" class="modal"
            v-model:visible="visible" :align-center="false" title-align="start" render-to-body>
            <div v-loading="initLoading">
                <a-form :model="createForm" :layout="isMobile ? 'vertical' : 'horizontal'" ref="createRef"
                    :rules="createRules">
                    <a-row :gutter="20">
                        <a-col :md="12" :xs="24">
                            <a-form-item :label-col-flex="labelColFlex" label="姓名" field="real_name">
                                <a-input v-model="createForm.real_name" placeholder="请输入姓名"></a-input>
                            </a-form-item>
                            <a-form-item :label-col-flex="labelColFlex" label="账号" field="account">
                                <a-input v-model="createForm.account" placeholder="请输入账号"></a-input>
                            </a-form-item>
                            <a-form-item :label-col-flex="labelColFlex" label="邮箱" field="email">
                                <a-input v-model="createForm.email" placeholder="请输入邮箱"></a-input>
                            </a-form-item>
                        </a-col>
                        <a-col :md="12" :xs="24">
                            <a-form-item :label-col-flex="labelColFlex" label="头像" field="avatar">
                                <upload-btn v-model="createForm.avatar" count="1"></upload-btn>
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="20">
                        <a-col v-if="adminLevel" :md="12" :xs="24">
                            <a-form-item :label-col-flex="labelColFlex" label="角色" field="roles">
                                <a-select v-model="createForm.roles" multiple collapse-tags placeholder="选择角色"
                                    class="form-select-fil" @change="$forceUpdate()">
                                    <a-option v-for="item in roleSelect" :key="item.id" :value="item.id">{{
            item.role_name }}</a-option>
                                </a-select>
                            </a-form-item>
                        </a-col>
                        <a-col :md="12" :xs="24" v-else>
                            <a-form-item :label-col-flex="labelColFlex" label="角色：">
                                <div class="text-red" v-if="adminLevel == 0"><icon-user-group />超级管理员</div>
                                <div class="text-red" v-if="adminLevel == -1"><icon-face-smile-fill />开发者</div>
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="20">
                        <a-col :md="12" :xs="24">
                            <a-form-item :label-col-flex="labelColFlex" label="密码：" field="pwd">
                                <a-input v-model="createForm.pwd" type="password" placeholder="请输入密码"></a-input>
                            </a-form-item>
                        </a-col>
                        <a-col :md="12" :xs="24">
                            <a-form-item :label-col-flex="labelColFlex" label="确定密码：" field="conf_pwd">
                                <a-input v-model="createForm.conf_pwd" type="password" placeholder="请再次输入密码"></a-input>
                            </a-form-item>
                        </a-col>
                    </a-row>
                </a-form>
            </div>
            <template #footer>
                <a-space>
                    <a-button @click="close()">取消</a-button>
                    <a-button type="primary" @click="onCreateOk()" :loading="btnLoading"
                        :disabled="initLoading || btnLoading">确定</a-button>
                </a-space>
            </template>
        </a-modal>
    </div>
</template>

<script lang="ts">
export default {
    name: "systemAdminCreate",
};
</script>
<script lang="ts" setup>
import { ref, getCurrentInstance, nextTick, reactive, onMounted } from "vue";
import { createAdminApi, updateAdminApi, getDetailAdminApi } from "@/api/system/admin";
import { getRoleSelectList } from "@/api/system/role";
import { EnumItemType, Result, ResultError } from "@/types";
import { useEnumStore, useAppStore } from '@/store';
import { useSetting } from "@/hooks/useSetting";
import { storeToRefs } from "pinia";
import { ValidatedError } from "@arco-design/web-vue";

const { isMobile } = storeToRefs(useAppStore());

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const labelColFlex = ref<string>("80px");

const emit = defineEmits(["success"]);

const statusEnum = ref<EnumItemType[]>(useEnumStore().getEnumItem("system.admin.StatusEnum"));

const operation = ref<string>("create");

const operationId = ref<number>(0);

const adminLevel = ref<number>(1);

const visible = ref<boolean>(false);

const createRef = ref<HTMLElement>();

const createForm = ref<any>({
    account: "",
    email: "",
    real_name: "",
    avatar: "",
    roles: [],
    pwd: "",
    conf_pwd: "",
    status: 1,
});

const createRules: any = reactive({
    account: [{ required: true, message: "账号不能为空！", trigger: "blur" }],
    real_name: [{ required: true, message: "姓名不能为空！", trigger: "blur" }],
    email: [
        { required: true, message: "邮箱不能为空！", trigger: "blur" },
        {
            validator: (value: string | undefined, callback: (error?: string) => void) => {
                console.log($utils.isEmail(value))
                if (!$utils.isEmail(value)) {
                    callback('邮箱格式不正确')
                } else {
                    callback()
                }
            }, trigger: "blur"
        },
    ],
});

const initLoading = ref<boolean>(false);

const toInit = () => {
    if (!operationId.value) {
        return;
    }
    initLoading.value = true;
    getDetailAdminApi({ id: operationId.value })
        .then((res: Result) => {
            createForm.value.account = res.data.account;
            createForm.value.real_name = res.data.real_name;
            createForm.value.email = res.data.email;
            createForm.value.avatar = res.data.avatar;
            adminLevel.value = res.data.level;
            createForm.value.status = res.data.status.value;
            createForm.value.roles = res.data.roles;
            initLoading.value = false;
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};

const btnLoading = ref<boolean>(false);

const onCreateOk = () => {
    proxy?.$refs['createRef']?.validate((error: undefined | Record<string, ValidatedError>) => {
        if (error === undefined) {
            if (btnLoading.value) {
                return;
            }
            btnLoading.value = true;
            let operationApi: any = null;
            if (operation.value == "create") {
                operationApi = createAdminApi(createForm.value);
            } else {
                operationApi = updateAdminApi(
                    Object.assign(
                        {
                            id: operationId.value,
                        },
                        createForm.value
                    )
                );
            }
            operationApi
                .then((res: Result) => {
                    $utils.successMsg(res.message);
                    close();
                    emit("success");
                    btnLoading.value = false;
                })
                .catch((err: ResultError) => {
                    $utils.errorMsg(err);
                    btnLoading.value = false;
                });
        }
    });
};

const open = (id: number = 0, level: number) => {
    adminLevel.value = 1;
    if (id != 0) {
        operation.value = "update";
        operationId.value = id;
    } else {
        operation.value = "create";
        createRules["pwd"] = [
            { required: true, message: "请输入密码", trigger: "blur" },
        ];
        createRules["conf_pwd"] = [
            { required: true, message: "请再次密码", trigger: "blur" },
        ];
    }
    nextTick(() => {
        toInit();
    });
    visible.value = true;
};

const close = () => {
    proxy?.$refs['createRef']?.resetFields();
    operationId.value = 0;
    createRules.pwd = [];
    createRules.conf_pwd = [];
    visible.value = false;
    return true;
};

const roleSelect = ref<any>([]);

const initRoleSelect = () => {
    getRoleSelectList()
        .then((res: Result) => {
            roleSelect.value = res.data;
        })
        .catch((err: ResultError) => {
            $utils.errorMsg(err);
        });
};
onMounted(() => {
    initRoleSelect();
});

defineExpose({ open });
</script>