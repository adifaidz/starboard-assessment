<script setup lang="ts">
import { AppFolder } from "@/interface";
import { PropType } from "vue";
import TreeView from "@/Components/Tree/TreeView.vue";

defineProps({
  folders: Array as PropType<Array<AppFolder>>,
});
</script>

<template>
  <aside
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
    aria-label="Sidebar"
  >
    <TreeView v-bind:nodes="folders" />
  </aside>
</template>
