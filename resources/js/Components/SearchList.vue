<script setup lang="ts">
import { AppFile, AppFolder } from "@/interface";
import { PropType } from "vue";
import route from "@/../../vendor/tightenco/ziggy/src/js";
import FileList from "@/Components/FileList.vue";
import FolderList from "@/Components/FolderList.vue";

const props = defineProps({
  files: Array as PropType<Array<AppFile>>,
  folders: Array as PropType<Array<AppFolder>>,
});
</script>

<template>
  <div class="flex flex-col p-6 gap-20">
    <FolderList v-if="folders && folders.length" :folders="folders" />
    <FileList v-if="files && files.length" :files="files" />
  </div>
</template>
