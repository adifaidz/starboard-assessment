<script setup lang="ts">
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { Inertia, Method } from "@inertiajs/inertia";

const currentFolderId = usePage().props.value.currentFolderId;
const form = useForm({
  files: [] as File[],
});

const submit = () => {
  form.post(route("app.files.store", { folder: currentFolderId }), {
    preserveScroll: true,
    onSuccess: () =>
      Inertia.visit(route("app.dashboard", { folder: currentFolderId }), {
        method: Method.GET,
        preserveScroll: true,
        only: ["folders", "files"],
      }),
  });
};
</script>

<template>
  <form @submit.prevent="submit" class="flex gap-2 content-center">
    <div>
      <input
        class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
        type="file"
        @input="
          form.files = Array.from(($event.target as HTMLInputElement).files)
        "
        multiple
      />
    </div>
    <button
      type="submit"
      class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
    >
      Upload
    </button>
    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
      {{ form.progress.percentage }}%
    </progress>
  </form>
</template>
