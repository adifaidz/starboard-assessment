<script setup lang="ts">
import { AppFolder, ModalType } from "@/interface";
import { PropType, ref, inject } from "vue";
import DangerButton from "@/Components/Ui/DangerButton.vue";
import SecondaryButton from "@/Components/Ui/SecondaryButton.vue";
import PrimaryButton from "@/Components/Ui/PrimaryButton.vue";
import InputError from "@/Components/Ui/InputError.vue";
import InputLabel from "@/Components/Ui/InputLabel.vue";
import TextInput from "@/Components/Ui/TextInput.vue";
import Modal from "@/Components/Ui/Modal.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import route from "@/../../vendor/tightenco/ziggy/src/js";

const defaultModalStates = {
  update: false,
  delete: false,
};

const props = defineProps({
  folders: Array as PropType<Array<AppFolder>>,
  onUpdateSuccess: Function,
  onDeleteSuccess: Function,
});

const currentSelectedFolderId = ref(null as string);
const modalStates = ref(defaultModalStates);

const updateFolderForm = useForm({
  name: null as string,
});
const deleteFolderForm = useForm({});

const closeFolderModal = () => {
  modalStates.value = defaultModalStates;
  updateFolderForm.reset();
  deleteFolderForm.reset();
};

const openModal = (folder: AppFolder, type: ModalType) => {
  currentSelectedFolderId.value = folder.id;

  if (type === ModalType.UPDATE) {
    updateFolderForm.name = folder.name;
  }

  modalStates.value = { ...defaultModalStates, [type]: true };
};

const updateFolder = () => {
  updateFolderForm.put(
    route("app.folders.update", {
      folder: currentSelectedFolderId.value,
    }) as string,
    {
      preserveScroll: true,
      onSuccess: () => {
        closeFolderModal();
        if (props.onUpdateSuccess) props.onUpdateSuccess();
      },
    }
  );
};

const deleteFolder = () => {
  deleteFolderForm.delete(
    route("app.folders.destroy", {
      folder: currentSelectedFolderId.value,
    }) as string,
    {
      preserveScroll: true,
      onSuccess: () => {
        closeFolderModal();
        if (props.onDeleteSuccess) props.onDeleteSuccess();
      },
    }
  );
};
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
  >
    <slot name="header" />
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead
          class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
        >
          <tr>
            <th scope="col" class="py-3 px-6 w-1/2">Folder</th>
            <th scope="col" class="py-3 px-6 w-2/12 text-center">Created on</th>
            <th scope="col" class="py-3 px-6 w-1/12 text-center">Location</th>
            <th scope="col" class="py-3 px-6 w-2/12 text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="folder in folders"
            :key="folder.id"
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
          >
            <th
              scope="row"
              class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
            >
              {{ folder.name }}
            </th>
            <td class="py-4 px-6 text-center">
              {{ new Date(folder.created_at).toDateString() }}
            </td>
            <td class="py-4 px-6 text-center">
              <Link
                :href="(route('app.dashboard', {folder: folder.parent.id}) as string)"
                class="hover:underline hover:text-gray-300"
                >{{ folder.parent.path }}</Link
              >
            </td>
            <td class="py-4 px-6 text-center">
              <PrimaryButton
                class="rounded-r-none"
                @click="() => openModal(folder, ModalType.UPDATE)"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  class="w-4 h-4"
                >
                  <path
                    d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z"
                  />
                </svg>
              </PrimaryButton>
              <DangerButton
                class="rounded-l-none"
                @click="() => openModal(folder, ModalType.DELETE)"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                  class="w-4 h-4"
                >
                  <path
                    fill-rule="evenodd"
                    d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z"
                    clip-rule="evenodd"
                  />
                </svg>
              </DangerButton>
              <Modal :show="modalStates.update" @close="closeFolderModal">
                <div class="p-6">
                  <div class="mt-6">
                    <InputLabel for="name" value="Name" class="sr-only" />

                    <TextInput
                      id="name"
                      v-model="updateFolderForm.name"
                      type="text"
                      class="mt-1 block w-full"
                      placeholder="Name"
                      @keyup.enter="updateFolder"
                    />

                    <InputError
                      :message="updateFolderForm.errors.name"
                      class="mt-2"
                    />
                  </div>

                  <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeFolderModal">
                      Cancel
                    </SecondaryButton>

                    <PrimaryButton
                      class="ml-3"
                      :class="{ 'opacity-25': updateFolderForm.processing }"
                      :disabled="updateFolderForm.processing"
                      @click="updateFolder"
                    >
                      Update
                    </PrimaryButton>
                  </div>
                </div>
              </Modal>
              <Modal :show="modalStates.delete" @close="closeFolderModal">
                <div class="p-6">
                  <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                  >
                    Are you sure you want to delete this file?
                  </h2>

                  <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeFolderModal">
                      Cancel
                    </SecondaryButton>

                    <DangerButton
                      class="ml-3"
                      :class="{ 'opacity-25': deleteFolderForm.processing }"
                      :disabled="deleteFolderForm.processing"
                      @click="deleteFolder"
                    >
                      Delete File
                    </DangerButton>
                  </div>
                </div>
              </Modal>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
