<script setup lang="ts">
import { TreeNode as TreeNodeType } from "@/interface";
import TreeNode from "./TreeNode.vue";
import { PropType } from "vue";

defineProps({
  nodes: Array as PropType<Array<TreeNodeType>>,
});
</script>

<template>
  <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
    <div
      class="p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group dark:text-white"
    >
      My Storage
    </div>
    <ul class="space-y-2">
      <TreeNode v-for="node in nodes" :node="node" />
    </ul>
  </div>
</template>
