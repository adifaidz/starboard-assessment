<script setup lang="ts">
import { PropType, ref, computed } from "vue";
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { Inertia, Method } from "@inertiajs/inertia";
import { TreeNode as TreeNodeType } from "@/interface";
import { Menu } from "floating-vue";
import TextInput from "@/Components/Ui/TextInput.vue";
import PrimaryButton from "@/Components/Ui/PrimaryButton.vue";
import route from "@/../../vendor/tightenco/ziggy/src/js";
import DangerButton from "@/Components/Ui/DangerButton.vue";

const props = defineProps({
  parentId: String,
  node: Object as PropType<TreeNodeType>,
});
const emit = defineEmits(["nodeExpanded"]);

const { props: $props } = usePage();
const isCurrentFolder = computed(() => $props.value.folderId === props.node.id);
const isExpanded = ref(isCurrentFolder.value);
const isRenaming = ref(false);
const isDeleting = ref(false);
const nameInput = ref(null);

const renameForm = useForm({
  name: props.node.name,
});
const deleteForm = useForm({});

if (isExpanded.value) {
  emit("nodeExpanded", props.node);
}

const onNodeClick = () => {
  if (isRenaming.value || isDeleting.value) return;
  isExpanded.value = isCurrentFolder.value ? !isExpanded.value : true;

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
  isExpanded.value = !isExpanded.value;
};
const onChildNodeExpand = () => {
  if (isExpanded.value) return;

  isExpanded.value = true;
  emit("nodeExpanded", props.node);
};

const toggleRename = () => {
  isRenaming.value = !isRenaming.value;
};
const toggleDelete = () => {
  isDeleting.value = !isDeleting.value;
};

const renameFolder = () => {
  renameForm.put(
    route("app.folders.update", { folder: props.node.id }) as string,
    {
      preserveState: true,
      only: ["folders", "folderNodes"],
      onSuccess: () => {
        toggleRename();
        renameForm.reset();
      },
    }
  );
};
const deleteFolder = () => {
  deleteForm.delete(
    route("app.folders.destroy", { folder: props.node.id }) as string,
    {
      preserveState: true,
      only: ["folders", "folderNodes"],
      onSuccess: () => {
        Inertia.visit(
          route("app.dashboard", { folder: props.parentId }) as string,
          {
            preserveState: true,
            method: Method.GET,
            only: ["files", "folderId"],
          }
        );
      },
    }
  );
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
      <template v-if="!isRenaming && !isDeleting">
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
        <Menu :triggers="['click']" placement="right">
          <button class="dark:text-white w-6 h-6">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              strokeWidth="{1.5}"
              stroke="currentColor"
              className="w-6 h-6"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"
              />
            </svg>
          </button>

          <template #popper>
            <PrimaryButton
              class="rounded-r-none"
              type="button"
              @click="toggleRename"
            >
              Rename
            </PrimaryButton>
            <PrimaryButton
              class="rounded-l-none"
              type="button"
              @click="toggleDelete"
            >
              Delete
            </PrimaryButton>
          </template>
        </Menu>
      </template>
      <template v-else-if="isRenaming">
        <TextInput
          id="name"
          ref="nameInput"
          v-model="renameForm.name"
          type="text"
          class="block rounded-r-none w-11/12"
          placeholder="Name"
          @keyup.enter="renameFolder"
        />
        <PrimaryButton
          class="rounded-l-none"
          type="button"
          @click="toggleRename"
        >
          Cancel
        </PrimaryButton>
      </template>
      <template v-else-if="isDeleting">
        <DangerButton
          class="rounded-none w-1/2"
          type="button"
          @click="deleteFolder"
        >
          Delete?
        </DangerButton>
        <PrimaryButton
          class="rounded-none w-1/2"
          type="button"
          @click="toggleDelete"
        >
          Cancel
        </PrimaryButton>
      </template>
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
