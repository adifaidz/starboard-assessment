<script setup lang="ts">
import { AppFile, ModalType, SelectLabel } from "@/interface";
import { PropType, ref, computed } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Multiselect from "vue-multiselect";
import DangerButton from "@/Components/Ui/DangerButton.vue";
import SecondaryButton from "@/Components/Ui/SecondaryButton.vue";
import PrimaryButton from "@/Components/Ui/PrimaryButton.vue";
import InputError from "@/Components/Ui/InputError.vue";
import InputLabel from "@/Components/Ui/InputLabel.vue";
import TextInput from "@/Components/Ui/TextInput.vue";
import Modal from "@/Components/Ui/Modal.vue";
import route from "@/../../vendor/tightenco/ziggy/src/js";

const defaultModalStates = {
  update: false,
  delete: false,
};

const props = defineProps({
  files: Array as PropType<Array<AppFile>>,
});

const currentSelectedFileId = ref(null as string);
const modalStates = ref(defaultModalStates);

const labelOptions = ref([] as SelectLabel[]);
const labelValues = ref([] as SelectLabel[]);

const updateFileForm = useForm({
  name: null as string,
  labels: [] as string[],
});

const deleteFileForm = useForm({});

const closeFileModal = () => {
  modalStates.value = defaultModalStates;
  updateFileForm.reset();
  deleteFileForm.reset();
};

const toSelectLabel = (label) => ({
  name: label,
  code: label,
});
const toSelectLabels = (labels: string[]) => labels.map(toSelectLabel);

const openModal = (file: AppFile, type: ModalType) => {
  currentSelectedFileId.value = file.id;

  if (type === ModalType.UPDATE) {
    updateFileForm.name = file.name;

    const selectLabels = toSelectLabels(file.labels);
    labelOptions.value = [...selectLabels];
    labelValues.value = [...selectLabels];
  }

  modalStates.value = { ...defaultModalStates, [type]: true };
};

const addLabel = (newLabel: string) => {
  const selectLabel = toSelectLabel(newLabel);
  labelOptions.value.push(selectLabel);
  labelValues.value.push(selectLabel);
};

const updateFile = () => {
  updateFileForm.labels = labelValues.value.map((label) => label.name);
  updateFileForm.put(
    route("app.files.update", { file: currentSelectedFileId.value }) as string,
    {
      preserveScroll: true,
      onSuccess: () => {
        closeFileModal();
      },
      onFinish: () => {
        updateFileForm.reset();
      },
    }
  );
};

const deleteFile = () => {
  deleteFileForm.delete(
    route("app.files.destroy", { file: currentSelectedFileId.value }) as string,
    {
      preserveScroll: true,
      onSuccess: () => {
        closeFileModal();
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
      <table
        class="table-fixed w-full text-sm text-left text-gray-500 dark:text-gray-400"
      >
        <thead
          class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
        >
          <tr>
            <th scope="col" class="py-3 px-6 w-4/12">File</th>
            <th scope="col" class="py-3 px-6 w-3/12 text-center">Labels</th>
            <th scope="col" class="py-3 px-6 w-2/12 text-center">
              Uploaded on
            </th>
            <th scope="col" class="py-3 px-6 w-1/12 text-center">Size</th>
            <th scope="col" class="py-3 px-6 w-2/12 text-center">Action</th>
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
              <a
                target="_blank"
                :href="route('app.files.download', {file: file.id}) as string"
              >
                {{ file.name }}
              </a>
            </th>
            <td class="py-4 px-6 space-x-2 space-y-2">
              <template v-if="file.labels.length">
                <div
                  v-for="label in file.labels"
                  class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-green-500 text-white rounded-full"
                >
                  {{ label }}
                </div>
              </template>
              <span v-else> - </span>
            </td>
            <td class="py-4 px-6 text-center">
              {{ new Date(file.created_at).toDateString() }}
            </td>
            <td class="py-4 px-6 text-center">{{ file.size }}</td>
            <td class="py-4 px-6 text-center">
              <PrimaryButton
                class="rounded-r-none"
                @click="() => openModal(file, ModalType.UPDATE)"
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
                @click="() => openModal(file, ModalType.DELETE)"
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
              <Modal :show="modalStates.update" @close="closeFileModal">
                <div class="p-6">
                  <div class="mt-6">
                    <InputLabel for="name" value="Name" class="sr-only" />

                    <TextInput
                      id="name"
                      v-model="updateFileForm.name"
                      type="text"
                      class="mt-1 block w-full"
                      placeholder="Name"
                      @keyup.enter="updateFile"
                    />

                    <InputError
                      :message="updateFileForm.errors.name"
                      class="mt-2"
                    />
                  </div>
                  <div class="mt-6">
                    <InputLabel for="labels" value="Labels" class="sr-only" />

                    <Multiselect
                      class="vue-multiselect border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm mt-1 block w-full"
                      v-model="labelValues"
                      tag-placeholder="Add this label"
                      placeholder="Add a label"
                      label="name"
                      track-by="code"
                      :options="labelOptions"
                      :multiple="true"
                      :taggable="true"
                      @tag="addLabel"
                    />

                    <InputError
                      :message="updateFileForm.errors.labels"
                      class="mt-2"
                    />
                  </div>

                  <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeFileModal">
                      Cancel
                    </SecondaryButton>

                    <PrimaryButton
                      class="ml-3"
                      :class="{ 'opacity-25': updateFileForm.processing }"
                      :disabled="updateFileForm.processing"
                      @click="updateFile"
                    >
                      Update
                    </PrimaryButton>
                  </div>
                </div>
              </Modal>
              <Modal :show="modalStates.delete" @close="closeFileModal">
                <div class="p-6">
                  <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                  >
                    Are you sure you want to delete this file?
                  </h2>

                  <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeFileModal">
                      Cancel
                    </SecondaryButton>

                    <DangerButton
                      class="ml-3"
                      :class="{ 'opacity-25': deleteFileForm.processing }"
                      :disabled="deleteFileForm.processing"
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

<style lang="css" scoped>
.vue-multiselect :deep(.multiselect__tags) {
  /* border-gray-300 */
  --tw-border-opacity: 1;
  border-color: rgb(209 213 219 / var(--tw-border-opacity));

  /* rounded-md */
  border-radius: 0.375rem /* 6px */;

  /* shadow-sm */
  --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
  box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000),
    var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);

  /* text-sm */
  font-size: 0.875rem /* 14px */;
  line-height: 1.25rem /* 20px */;

  /* mt-1 */
  margin-top: 0.25rem /* 4px */;

  /* block */
  display: block;

  /* w-full */
  width: 100%;
}

.vue-multiselect :deep(.multiselect__tags:focus) {
  /* focus:border-indigo-500 */
  --tw-border-opacity: 1;
  border-color: rgb(104 117 245 / var(--tw-border-opacity));

  /* focus:ring-indigo-500 */
  --tw-ring-opacity: 1;
  --tw-ring-color: rgb(104 117 245 / var(--tw-ring-opacity));
}

.vue-multiselect :deep(.multiselect__input) {
  /* border-gray-300 */
  --tw-border-opacity: 1;
  border-color: rgb(209 213 219 / var(--tw-border-opacity));

  /* rounded-md */
  border-radius: 0.375rem /* 6px */;

  /* shadow-sm */
  --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
  box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000),
    var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);

  /* text-sm */
  font-size: 0.875rem /* 14px */;
  line-height: 1.25rem /* 20px */;

  /* mt-1 */
  margin-top: 0.25rem /* 4px */;

  /* block */
  display: block;

  /* w-full */
  width: 100%;
}

.vue-multiselect :deep(.multiselect__input:focus) {
  outline: none;
  box-shadow: none;
}

@media (prefers-color-scheme: dark) {
  .vue-multiselect :deep(.multiselect__tags) {
    /* dark:border-gray-700 */
    --tw-border-opacity: 1;
    border-color: rgb(55 65 81 / var(--tw-border-opacity));

    /* dark:bg-gray-900 */
    --tw-bg-opacity: 1;
    background-color: rgb(17 24 39 / var(--tw-bg-opacity));

    /* dark:text-gray-300 */
    --tw-text-opacity: 1;
    color: rgb(209 213 219 / var(--tw-text-opacity));
  }

  .vue-multiselect :deep(.multiselect__tags:focus) {
    /* dark:focus:border-indigo-600 */
    --tw-border-opacity: 1;
    border-color: rgb(88 80 236 / var(--tw-border-opacity));

    /* dark:focus:ring-indigo-600 */
    --tw-ring-opacity: 1;
    --tw-ring-color: rgb(88 80 236 / var(--tw-ring-opacity));
  }

  .vue-multiselect :deep(.multiselect__input) {
    /* dark:border-gray-700 */
    --tw-border-opacity: 1;
    border-color: rgb(55 65 81 / var(--tw-border-opacity));

    /* dark:bg-gray-900 */
    --tw-bg-opacity: 1;
    background-color: rgb(17 24 39 / var(--tw-bg-opacity));

    /* dark:text-gray-300 */
    --tw-text-opacity: 1;
    color: rgb(209 213 219 / var(--tw-text-opacity));
  }
}
</style>
