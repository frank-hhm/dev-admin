<template>
  <template v-if="title == ''">
    <div class="layout-body-main" :style="styles">
      <slot></slot>
    </div>
  </template>
  <template v-else>
    <div class="layout-body-main-header" v-if="title">
      <div class="layout-body-main-header-title">{{ title }}</div>
      <div>
        <slot name="right"></slot>
      </div>
    </div>
    <div class="layout-body-main" :style="styles">
      <slot></slot>
    </div>
  </template>
</template>
<script lang="ts">
export default {
  name: "LayoutBody",
};
</script>
<script lang="ts" setup>
import { computed, StyleValue } from "vue";

const props = withDefaults(
  defineProps<{
    height?: string | boolean;
    title?: string;
    padding?: string;
  }>(),
  {
    height: false,
    title: "",
    padding: "20px",
  }
);

const styles = computed<StyleValue>(() => {
  return [
    props.height ? `height:calc(${props.height} - 42px)` : "",
    `padding:${props.padding}`,
  ];
});
</script>
<style scoped>
.layout-body-main {
  padding: 20px;
  background: var(--color-bg-1);
  border-radius: var(--base-radius);
  border: 1px solid var(--color-border-1);
}

.layout-body-main-header {
  background: #fff;
  min-height: 20px;
  border-top: 1px solid var(--color-border-1);
  border-right: 1px solid var(--color-border-1);
  border-left: 1px solid var(--color-border-1);
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.layout-body-main-header-title {
  font-size: 16px;
}
</style>