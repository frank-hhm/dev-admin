<template>
    <layout-body height="100%">
        <editor ref="editorRef" height="600px" v-model="content"></editor>
    </layout-body>
</template>

<script lang="ts" setup>
import { ref } from "vue";

const editorRef = ref<HTMLElement>();

const content = ref<string>("");
</script>