<script setup lang="ts">
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import Modal from "@/Components/Ui/Modal.vue";
import InputError from "@/Components/Ui/InputError.vue";
import InputLabel from "@/Components/Ui/InputLabel.vue";
import TextInput from "@/Components/Ui/TextInput.vue";
import SecondaryButton from "@/Components/Ui/SecondaryButton.vue";
import PrimaryButton from "@/Components/Ui/PrimaryButton.vue";
import { Inertia, Method } from "@inertiajs/inertia";
import { ref, useAttrs, computed } from "vue";
import route from "@/../../vendor/tightenco/ziggy/src/js";

const props = defineProps({
  folderId: String,
});
const attrs = useAttrs();

const isModalOpen = ref(false);
const nameInput = ref(null);

const form = useForm({
  name: null as string,
  parentId: null as string,
});

const submit = () => {
  form.parentId = props.folderId;
  form.post(route("app.folders.store") as string, {
    preserveScroll: true,
    onSuccess: () => {
      closeModal();
    },
    onError: () => nameInput.value.focus(),
    onFinish: () => form.reset(),
  });
};

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};
</script>

<template>
  <PrimaryButton v-bind="attrs" @click="openModal" type="button">
    Add Folder
  </PrimaryButton>
  <Modal :show="isModalOpen" @close="closeModal">
    <div class="p-6">
      <div class="mt-6">
        <InputLabel for="name" value="Name" class="sr-only" />

        <TextInput
          id="name"
          ref="nameInput"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full"
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
