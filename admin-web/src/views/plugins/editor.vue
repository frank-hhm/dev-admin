<template>
    <div>
        <editor ref="editorRef" height="600px" v-model="content"></editor>
    </div>
</template>

<script lang="ts" setup>
import { ref } from "vue";

const editorRef = ref<HTMLElement>();

const content = ref<string>("");

</script>