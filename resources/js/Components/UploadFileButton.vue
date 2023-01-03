<script setup lang="ts">
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import PrimaryButton from "@/Components/Ui/PrimaryButton.vue";
import route from "@/../../vendor/tightenco/ziggy/src/js";

const props = defineProps({
  folderId: String,
});

const fileInput = ref(null);

const form = useForm({
  files: [] as File[],
  parentId: null as string,
});

const submit = () => {
  form.parentId = props.folderId;
  form.post(route("app.files.store") as string, {
    preserveScroll: true,
    onFinish: () => {
      fileInput.value.value = null;
      form.files = [];
    },
  });
};
</script>

<template>
  <form @submit.prevent="submit" class="flex content-center">
    <div>
      <input
        ref="fileInput"
        class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg rounded-r-none cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
        type="file"
        @input="
          form.files = Array.from(($event.target as HTMLInputElement).files)
        "
        multiple
      />
    </div>
    <PrimaryButton
      type="submit"
      :disabled="form.processing"
      class="rounded-l-none"
      :class="{ 'opacity-25': form.processing }"
    >
      Upload
    </PrimaryButton>
  </form>
</template>
