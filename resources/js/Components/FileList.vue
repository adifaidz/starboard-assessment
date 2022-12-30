<script setup lang="ts">
import { AppFile } from "@/interface";
import { PropType, ref, inject } from "vue";
import DangerButton from "@/Components/Ui/DangerButton.vue";
import SecondaryButton from "@/Components/Ui/SecondaryButton.vue";
import Modal from "@/Components/Ui/Modal.vue";
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { Inertia, Method } from "@inertiajs/inertia";
import route from "@/../../vendor/tightenco/ziggy/src/js";

defineProps({
  files: Array as PropType<Array<AppFile>>,
});

const pageFolderId = usePage().props.value.folderId;
const currentSelectedFileId = ref(null as string);
const confirmingFileDeletion = ref(false);
const form = useForm({});

const confirmFileDeletion = (file: AppFile) => {
  currentSelectedFileId.value = file.id;
  confirmingFileDeletion.value = true;
};

const closeModal = () => {
  confirmingFileDeletion.value = false;
};

const partialReload = () => {
  Inertia.visit(route("app.dashboard", { folder: pageFolderId }) as string, {
    method: Method.GET,
    preserveScroll: true,
    only: ["folders", "files"],
  });
};

const deleteFile = () => {
  form.delete(
    route("app.files.destroy", { file: currentSelectedFileId.value }) as string,
    {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
        partialReload();
      },
    }
  );
};
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
  >
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead
          class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
        >
          <tr>
            <th scope="col" class="py-3 px-6 w-1/2">File</th>
            <th scope="col" class="py-3 px-6 w-2/12 text-center">Labels</th>
            <th scope="col" class="py-3 px-6 w-2/12 text-center">
              Uploaded on
            </th>
            <th scope="col" class="py-3 px-6 w-1/12 text-center">Size</th>
            <th scope="col" class="py-3 px-6 w-1/12 text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="file in files"
            :key="file.id"
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
          >
            <th
              scope="row"
              class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
            >
              {{ file.name }}
            </th>
            <td class="py-4 px-6 text-center">
              <li v-if="file.labels.length" v-for="label in file.labels">
                {{ label }}
              </li>
              <span v-else> - </span>
            </td>
            <td class="py-4 px-6 text-center">
              {{ new Date(file.created_at).toDateString() }}
            </td>
            <td class="py-4 px-6 text-center">20MB</td>
            <td class="py-4 px-6 text-center">
              <DangerButton @click="() => confirmFileDeletion(file)">
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
              <Modal :show="confirmingFileDeletion" @close="closeModal">
                <div class="p-6">
                  <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                  >
                    Are you sure you want to delete this file?
                  </h2>

                  <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                      Cancel
                    </SecondaryButton>

                    <DangerButton
                      class="ml-3"
                      :class="{ 'opacity-25': form.processing }"
                      :disabled="form.processing"
                      @click="deleteFile"
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
