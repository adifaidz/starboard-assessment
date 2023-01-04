<script setup lang="ts">
import TextInput from "@/Components/Ui/TextInput.vue";
import PrimaryButton from "@/Components/Ui/PrimaryButton.vue";
import { useAttrs } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import route from "@/../../vendor/tightenco/ziggy/src/js";

const attrs = useAttrs();
const form = useForm({
  q: "" as string,
});

const onSearch = () => {
  if (!form.q) return;

  form.get(route("app.search") as string, {});
};
</script>

<template>
  <div class="flex">
    <TextInput
      v-bind="attrs"
      v-model="form.q"
      placeholder="Search"
      type="text"
      class="text-gray-900 border border-gray-300 rounded-lg rounded-r-none cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
      @keyup.enter="onSearch"
    />
    <PrimaryButton
      class="rounded-l-none"
      :disabled="form.processing"
      :class="{ 'opacity-25': form.processing }"
      @click="onSearch"
    >
      Search
    </PrimaryButton>
  </div>
</template>
