<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Sidebar from "@/Components/SideBar.vue";
import SearchList from "@/Components/SearchList.vue";
import { Head, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed } from "vue";
import { AppFile, AppFolder } from "@/interface";

const { props: $props } = usePage();
const folders = computed(() => $props.value.folders as AppFolder[]);
const files = computed(() => $props.value.files as AppFile[]);
const folderNodes = computed(() => $props.value.folderNodes as AppFolder[]);
</script>

<template>
  <Head title="Search" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Search
      </h2>
    </template>

    <div class="flex flex-col py-12 max-w-full mx-auto sm:px-6 lg:px-12">
      <div class="flex gap-6">
        <Sidebar :folders="folderNodes" class="w-3/12" />
        <SearchList :files="files" :folders="folders" class="w-9/12" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
