<script setup lang="ts">
import { TreeNode as TreeNodeType } from "@/interface";
import { PropType, ref } from "vue";
import { Inertia, Method } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/inertia-vue3";
import route from "@/../../vendor/tightenco/ziggy/src/js";

const pageFolderId = usePage().props.value.folderId as string;
const props = defineProps({
  parentId: String,
  node: Object as PropType<TreeNodeType>,
});
const emit = defineEmits(["nodeExpanded"]);
console.log("pageFolderId", pageFolderId);
const isCurrentFolder = pageFolderId === props.node.id;
const isExpanded = ref(isCurrentFolder);

if (isExpanded.value) {
  emit("nodeExpanded", props.node);
}

const onNodeClick = () => {
  console.log("onNodeIconClick");
  isExpanded.value = !isExpanded.value;

  Inertia.visit(
    route("app.dashboard", {
      folder: isExpanded.value ? props.node.id : props.parentId,
    }) as string,
    {
      preserveState: true,
      method: Method.GET,
      only: ["files", "folderId"],
    }
  );
};

const onNodeIconClick = () => {
  console.log("onNodeIconClick");
  isExpanded.value = !isExpanded.value;
};

const onChildNodeExpand = () => {
  if (isExpanded.value) return;

  isExpanded.value = true;
  emit("nodeExpanded", props.node);
};
</script>

<template>
  <li>
    <button
      class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group dark:text-white"
      :class="{
        'bg-gray-100 dark:bg-gray-700': isCurrentFolder,
        'hover:bg-gray-100 dark:hover:bg-gray-500': !isCurrentFolder,
      }"
      @click.self="onNodeClick"
    >
      <svg
        v-if="isExpanded"
        class="w-4 h-4"
        viewBox="0 0 24 24"
        fill="currentColor"
        xmlns="http://www.w3.org/2000/svg"
        @click.self="onNodeIconClick"
      >
        <path
          fillRule="evenodd"
          d="M12.53 16.28a.75.75 0 01-1.06 0l-7.5-7.5a.75.75 0 011.06-1.06L12 14.69l6.97-6.97a.75.75 0 111.06 1.06l-7.5 7.5z"
          clipRule="evenodd"
        />
      </svg>
      <svg
        v-else
        class="w-4 h-4"
        viewBox="0 0 24 24"
        fill="currentColor"
        xmlns="http://www.w3.org/2000/svg"
        @click.self="onNodeIconClick"
      >
        <path
          fillRule="evenodd"
          d="M16.28 11.47a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 011.06-1.06l7.5 7.5z"
          clipRule="evenodd"
        />
      </svg>

      <span
        class="flex-1 ml-3 text-left whitespace-nowrap"
        @click.self="onNodeClick"
      >
        {{ node.name }}
      </span>
    </button>
    <ul
      v-if="node.nodes.length"
      v-show="isExpanded"
      class="pl-6 py-2 space-y-2"
    >
      <TreeNode
        v-for="child in node.nodes"
        :key="child.id"
        :node="child"
        :parent-id="node.id"
        @node-expanded="onChildNodeExpand"
      />
    </ul>
  </li>
</template>
