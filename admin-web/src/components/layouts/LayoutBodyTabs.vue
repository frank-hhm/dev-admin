<template>
  <div class="layout-body-tabs" :class="[
    heightFil ? 'height-fil' : '',
    border?'border':''
  ]">
    <div class="layout-body-tabs-nav">
      <div class="layout-body-tabs-nav-wrap">
        <div class="layout-body-tabs-nav-scroll">
          <div class="layout-body-tabs-nav-head">
            <template v-for="(item, index) in tabs" :key="index">
              <div class="layout-body-tabs-nav-head-item" :class="[
    item.value == tabActive ? 'is-active' : '',
    loading && item.value != tabActive ? 'is-loading' : ''
  ]" @click="selected(item)">
                {{ item.name }}
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
    <div class="layout-body-tabs-main">
      <slot></slot>
    </div>
  </div>
</template>
<script lang="ts">
export default {
  name: "LayoutBodyTabs",
};
</script>
<script lang="ts" setup>
import { ref } from "vue";
import { EnumType } from "@/types";
const props = withDefaults(
  defineProps<{
    tabs?: EnumType;
    modelValue?: number | string;
    heightFil?: boolean;
    loading?: boolean;
    border?:boolean;
  }>(),
  {
    tabs: () => {
      return [];
    },
    modelValue: 0,
    heightFil: false,
    loading: false,
    border:true
  }
);

const emit = defineEmits(["update:modelValue", "update:loading", "change"]);

const tabActive = ref<number | string>(props.modelValue);

const selected = (item: { name: string; value: number | string }) => {
  if (!props.loading) {
    tabActive.value = item.value
    emit("update:modelValue", item.value);
    emit("change", item.value, item);
  }
};
</script>
<style scoped>
.layout-body-tabs {
  background: var(--color-bg-2);
  overflow: hidden;
}
.layout-body-tabs.border{
  border: 1px solid var(--color-border-1);
  border-radius: var(--base-radius-default);
}

.layout-body-tabs-main {
  position: relative;
}

.layout-body-tabs.height-fil {
  height: calc(100% - 2px);
}

.layout-body-tabs.height-fil .layout-body-tabs-main {
  height: calc(100% - 40px);
}

.layout-body-tabs-nav {
  background-color: var(--color-bg-2);
  margin: 0;
  padding: 0;
  height: 39px;
  position: relative;
}
.layout-body-tabs.border .layout-body-tabs-nav{
  border-bottom: 1px solid var(--color-border-1);
}
.layout-body-tabs-nav-wrap {
  overflow: hidden;
  margin-bottom: -1px;
  margin-left: -1px;
  margin-right: -1px;
  position: relative;
}

.layout-body-tabs-nav-scroll {
  overflow: hidden;
}

.layout-body-tabs-nav-head {
  white-space: nowrap;
  position: relative;
  float: left;
  z-index: 9;
}

.layout-body-tabs-nav-head-item {
  padding: 0 20px;
  height: 40px;
  box-sizing: border-box;
  line-height: 40px;
  display: inline-block;
  list-style: none;
  font-size: var(--base-size-default);
  font-weight: 500;
  color: #000000;
  position: relative;
  user-select: none;
  cursor: pointer;
  border: 1px solid transparent;
  margin-top: -1px;
  color: var(--color-text-1);
}

.layout-body-tabs-nav-head-item.is-active {
  color: rgba(var(--primary-6));
  background-color: var(--color-bg-5);
  border-right-color: var(--color-border-1);
  border-left-color: var(--color-border-1);
}

.layout-body-tabs-nav-head-item.is-loading {
  cursor: no-drop;
  color: rgba(var(--gray-6));
}
</style>