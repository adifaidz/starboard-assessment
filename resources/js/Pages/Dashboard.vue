<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Sidebar from "@/Components/SideBar.vue";
import FileList from "@/Components/FileList.vue";
import UploadFileButton from "@/Components/UploadFileButton.vue";
import AddFolderButton from "@/Components/AddFolderButton.vue";
import SearchInput from "@/Components/SearchInput.vue";
import { Head, usePage } from "@inertiajs/inertia-vue3";
import { computed } from "vue";
import { AppFile, AppFolder } from "@/interface";

const { props: $props } = usePage();
const pageFolderId = computed(() => $props.value.folderId as string);
const files = computed(() => $props.value.files as AppFile[]);
const folderNodes = computed(() => $props.value.folderNodes as AppFolder[]);
console.log(pageFolderId);
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Dashboard
      </h2>
    </template>

    <div class="flex flex-col py-12 max-w-full mx-auto sm:px-6 lg:px-12">
      <div class="flex mb-6 gap-6">
        <SearchInput class="flex-grow" />
        <UploadFileButton :folderId="pageFolderId" />
        <AddFolderButton :folderId="pageFolderId" />
      </div>
      <div class="flex gap-6">
        <Sidebar :folders="folderNodes" class="w-3/12" />
        <FileList :files="files" class="w-9/12" />
      </div></div
  ></AuthenticatedLayout>
</template>
