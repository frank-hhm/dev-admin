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
    padding: "var(--base-padding)",
  }
);

const styles = computed<StyleValue>(() => {
  return [
    props.height ? `height:calc(${props.height} - 2 * var(--base-padding) - 2px)` : "",
    `padding:${props.padding}`,
  ];
});
</script>
<style scoped>
.layout-body-main {
  padding: var(--base-padding);
  background: var(--color-bg-2);
  border-radius: var(--base-radius-default);
  border: 1px solid var(--color-border-1);
  position: relative;
}

.layout-body-main-header {
  background: var(--color-bg-1);
  min-height: 20px;
  border-top: 1px solid var(--color-border-1);
  border-right: 1px solid var(--color-border-1);
  border-left: 1px solid var(--color-border-1);
  padding: var(--base-padding);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.layout-body-main-header-title {
  /* font-size: var(--s); */
}
</style>