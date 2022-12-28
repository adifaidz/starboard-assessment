<script setup lang="ts">
import { useForm } from "@inertiajs/inertia-vue3";
import Modal from "@/Components/Ui/Modal.vue";
import InputError from "@/Components/Ui/InputError.vue";
import InputLabel from "@/Components/Ui/InputLabel.vue";
import TextInput from "@/Components/Ui/TextInput.vue";
import SecondaryButton from "@/Components/Ui/SecondaryButton.vue";
import PrimaryButton from "@/Components/Ui/PrimaryButton.vue";
import { ref } from "vue";

const props = defineProps({
  parentId: String,
});

const isModalOpen = ref(false);
const nameInput = ref(null);

const form = useForm({
  name: null as string,
  parentId: props.parentId as string,
});

const submit = () => {
  form.post(route("folders.store"), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => nameInput.value.focus(),
    onFinish: () => form.reset(),
  });
};

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  form.reset();
};
</script>

<template>
  <button
    @click="openModal"
    type="button"
    class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
  >
    Add Folder
  </button>
  <Modal :show="isModalOpen" @close="closeModal">
    <div class="p-6">
      <div class="mt-6">
        <InputLabel for="name" value="Name" class="sr-only" />

        <TextInput
          id="name"
          ref="nameInput"
          v-model="form.name"
          type="text"
          class="mt-1 block w-3/4"
          placeholder="Name"
          @keyup.enter="submit"
        />

        <InputError :message="form.errors.name" class="mt-2" />
      </div>

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

        <PrimaryButton
          class="ml-3"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
          @click="submit"
        >
          Add
        </PrimaryButton>
      </div>
    </div>
  </Modal>
</template>
