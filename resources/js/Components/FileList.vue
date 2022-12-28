<script setup lang="ts">
import { AppFile } from "@/interface";
import { PropType } from "vue";

defineProps({
  files: Array as PropType<Array<AppFile>>,
});
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
              <a
                href="#"
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                >Edit</a
              >
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
