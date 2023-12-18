
<template>
    <div class="editor-body">
        <Toolbar class="editor-body-border" id="toolbar" :editor="editorRef" :defaultConfig="toolbarConfig" :mode="mode" />
        <Editor :style="styles" v-model="valueHtml" :defaultConfig="editorConfig" :mode="mode" @onCreated="handleCreated"
            @onFocus="handleFocus" @onBlur="handleBlur" @onChange="handleChange" id="editor" />
        <mateSelectModal ref="mateSelectModalRef" v-if="imageModalVisible" @close="imageModalClose" @change="selectChange" :count="-1"></mateSelectModal>
        <mateSelectModal ref="mateSelectVideoModalRef"  v-if="videoModalVisible" @close="videoModalClose" @change="selectVideoChange" type="video" :count="1"></mateSelectModal>
    </div>
</template>
<script lang="ts" name="editor" setup>
import "@wangeditor/editor/dist/css/style.css"; // 引入 css
import {
    onBeforeUnmount,
    ref,
    shallowRef,
    onMounted,
    getCurrentInstance,
    computed,
    StyleValue,
    nextTick
} from "vue";
import { IDomEditor, Boot } from "@wangeditor/editor";
import { Editor, Toolbar } from "@wangeditor/editor-for-vue";
import mateSelectModal from "@/components/media/select-modal.vue";

const props = withDefaults(
    defineProps<{
        height?: string;
        modelValue?: string;
        disabled?: boolean;
    }>(),
    {
        height: "500px",
        modelValue: "",
        disabled: false,
    }
);

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const emit = defineEmits(["update:modelValue"]);

const styles = computed<StyleValue>(() => {
    return [`height: ${props.height}`];
});

const mode = ref<any>();

// 编辑器实例，必须用 shallowRef
const editorRef = shallowRef();

// 内容 HTML
const valueHtml = ref("<p></p>");

const mateSelectModalRef = ref<HTMLElement>();

const setFocus = () => {
    editorRef.value.focus();
};

const toolbarConfig = {
    toolbarKeys: [
        "headerSelect",
        "fontSize",
        "fontFamily",
        "lineHeight",
        "bold",
        "underline",
        "italic",
        "color",
        "bgColor",
        {
            key: "group-more-style",
            title: "更多",
            menuKeys: [
                "through",
                "blockquote",
                "sup",
                "sub",
                "divider",
                "clearStyle",
            ],
        },
        "bulletedList",
        "numberedList",
        {
            key: "group-justify",
            title: "对齐",
            menuKeys: [
                "justifyLeft",
                "justifyRight",
                "justifyCenter",
                "justifyJustify",
            ],
        },
        {
            key: "group-indent",
            title: "缩进",
            menuKeys: ["indent", "delIndent"],
        },
        "emotion",
        "insertTable",
        "undo",
        "redo",
        "codeBlock",
        "code",
        "insertLink",
        "fullScreen"
    ],
    insertKeys: {
        index: 22,
        keys: ["imageTool", "videoTool"],
    },
};

const isHTML = ref<boolean>(false);

const imageModalVisible = ref<boolean>(false);

const videoModalVisible = ref<boolean>(false);

// 初始化 MENU_CONF 属性

const editorConfig = {
    placeholder: "请输入内容...",
    MENU_CONF: {
        imageTool: {
            callback() {
                imageModalVisible.value = true
                nextTick(() => {
                    proxy?.$refs["mateSelectModalRef"]?.open([]);
                })
            },
        },
        videoTool: {
            callback() {
                videoModalVisible.value = true
                nextTick(() => {
                    proxy?.$refs["mateSelectVideoModalRef"]?.open([], 'video');
                })
            },
        },
        codeSelectLang: {
            // 代码语言
            codeLangs: [
                { text: 'CSS', value: 'css' },
                { text: 'HTML', value: 'html' },
                { text: 'XML', value: 'xml' },
                { text: 'PHP', value: 'php' },
                { text: 'Java', value: 'java' },
                { text: 'JavaScript', value: 'javascript' },
                { text: 'TypeScript', value: 'typescript' },
                { text: 'JSON', value: 'json' },
                { text: 'Vue', value: 'vue' },
                { text: 'React', value: 'react' },
                { text: 'Python', value: 'python' },
                { text: 'Go', value: 'go' },
                { text: 'C', value: 'c' },
                // 其他
            ]
        }
    },
};


// 组件销毁时，也及时销毁编辑器
onBeforeUnmount(() => {
    enable()
});

const enable = () => {
    const editor = editorRef.value;
    if (editor == null) return;
    if (props.disabled) {
        editor.disable();
    } else {
        editor.enable();
    }
}

const handleCreated = (editor: IDomEditor) => {
    editorRef.value = editor;
};
const handleFocus = (editor: IDomEditor) => {
    // console.log("focus", editor);
};
const handleBlur = (editor: IDomEditor) => {
    // console.log("blur", editor);
};
const handleChange = (editor: IDomEditor) => {
    emit("update:modelValue", editor.getHtml());
}

const selectChange = (data: string | string[]) => {
    const editor = editorRef.value;
    if (editor == null) {
        alert("kong");
        return;
    }
    //修复鼠标点击其他地方失去焦点问题
    editor.focus();
    if (typeof data == "object") {
        data.forEach((item: string) => {
            editor.dangerouslyInsertHtml(
                `<img style='width:100%;display: block;' src='${item}' />`
            );
        });
    } else {
        editor.dangerouslyInsertHtml(
            `<img style='width:100%;display: block;' src='${data}' />`
        );
    }
    editor.focus(true);
};

const imageModalClose = () => {
    imageModalVisible.value = false;
};

const videoModalClose = () => {
    videoModalVisible.value = false;
};

const selectVideoChange = (data: string | string[]) => {
    console.log(data)
    const editor = editorRef.value;
    if (editor == null) {
        alert("kong");
        return;
    }
    //修复鼠标点击其他地方失去焦点问题
    editor.focus();
    editor.dangerouslyInsertHtml(
        `<div data-w-e-type="video" data-w-e-is-void><video controls="true" width="auto" height="auto" src='${data}' type="video/mp4"></video></div>`
    );
    editor.focus(true);
};


const setContent = (content: string) => {
    editorRef.value.setHtml(content)
}

onMounted(() => {
    // setTimeout(() => {
    //     setFocus();
    // }, 1000);
});

defineExpose({ setFocus, setContent, enable });
</script>    
<style scoped>
.editor-body {
    border: 1px solid var(--color-border-1);
    border-radius: var(--base-radius);
}

.editor-body-border {
    border-bottom: 1px solid var(--color-border-1);
}
</style>