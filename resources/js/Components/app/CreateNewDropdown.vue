<template>
    <div>
        <Dropdown label="Create New" class="w-full">
            <template v-slot:icon>
                <PlusCircleIcon
                    class="-mr-1 ml-2 h-5 w-5 text-indigo-700"
                    aria-hidden="true"
                />
            </template>
    
            <template v-slot:items>
                <div class="px-1 py-1">
                    <MenuItem>
                        <button
                            class="flex gap-2 items-center w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-indigo-50 hover:border-indigo-100 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                            @click="showCreateFolderModal"
                        >
                            <FolderPlusIcon
                                class="h-5 w-5"
                                aria-hidden="true"
                            /> New Folder
                        </button>
                    </MenuItem>
                </div>
                <div class="px-1 py-1">
                    <FolderUploadMenuItem />
                    <FileUploadMenuItem />
                </div>
            </template>
        </Dropdown>
    
        <CreateFolderModal v-model="showModal" />

    </div>
</template>

<script setup>
//IMPORTS
import { MenuItem } from "@headlessui/vue";
import { FolderPlusIcon, PlusCircleIcon } from "@heroicons/vue/24/outline";
import { ref } from "vue";
import Dropdown from "../Dropdown.vue";
import CreateFolderModal from "./CreateFolderModal.vue";
import FileUploadMenuItem from './FileUploadMenuItem.vue'
import FolderUploadMenuItem from './FolderUploadMenuItem.vue'

//REFS
const showModal = ref(false);

//METHODS
const showCreateFolderModal = () => {
    showModal.value = true;
};


</script>
