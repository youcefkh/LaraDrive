<script setup>
import { onMounted, ref } from "vue";
import { emitter, FILE_UPLOADED } from "@/event-bus.js";
import Navigation from "../Components/app/Navigation.vue";
import SearchForm from "../Components/app/SearchForm.vue";
import UserSettingsDropdown from "../Components/app/UserSettingsDropdown.vue";
import { useForm, usePage } from "@inertiajs/vue3";

//USES
const page = usePage();
const uploadFilesForm = useForm(
    {
        files: [],
        parent_id: null,
    }
)

//REFS
const isDragOver = ref(false);

//METHODS
const handDrop = (e) => {
    isDragOver.value = false;
    const files = e.dataTransfer.files;
    uploadFiles(files)
};
const handDragOver = (e) => {
    const draggable = e.dataTransfer.types;
    isDragOver.value = draggable.includes("Files");
};
const handDragLeave = (e) => {
    isDragOver.value = false;
};

const uploadFiles = (files) => {
    uploadFilesForm.files = files;
    uploadFilesForm.parent_id = page.props.folder.id;
    uploadFilesForm.post(route("file.store"));
};

//HOOKS
onMounted(() => {
    emitter.on(FILE_UPLOADED, uploadFiles);
});
</script>

<template>
    <div class="h-screen bg-gray-50 flex w-full gap-4">
        <Navigation />

        <main class="flex flex-col flex-1 px-4 overflow-hidden">
            <div class="flex items-center justify-between w-full">
                <SearchForm />
                <UserSettingsDropdown />
            </div>

            <div
                class="flex flex-1 flex-col overflow-hidden mt-8"
                @drop.prevent="handDrop"
                @dragover.prevent="handDragOver"
                @dragleave.prevent="handDragLeave"
            >
                <div 
                    v-if="isDragOver"
                    class="flex justify-center w-full h-32 px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-md appearance-none cursor-pointer hover:border-gray-400 focus:outline-none"
                >
                    <span class="flex items-center space-x-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-gray-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                            />
                        </svg>
                        <span class="font-medium text-gray-600">
                            Drop files to upload
                        </span>
                    </span>
                </div>
                <div v-else>
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>
