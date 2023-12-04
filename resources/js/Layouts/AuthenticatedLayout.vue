<script setup>
import { onMounted, ref } from "vue";
import { emitter, FILE_UPLOADED } from "@/event-bus.js";
import Navigation from "../Components/app/Navigation.vue";
import SearchForm from "../Components/app/SearchForm.vue";
import UserSettingsDropdown from "../Components/app/UserSettingsDropdown.vue";
import { useForm, usePage } from "@inertiajs/vue3";

//USES
const page = usePage();
const uploadFilesForm = useForm({
    files: [],
    relative_paths: [],
    parent_id: null,
});

//REFS
const isDragOver = ref(false);

//METHODS
const handDrop = (e) => {
    isDragOver.value = false;
    const files = e.dataTransfer.files;
    uploadFiles(files);
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
    uploadFilesForm.relative_paths = [...files].map(
        (file) => file.webkitRelativePath,
    );
    uploadFilesForm.parent_id = page.props.folder.id;
    uploadFilesForm.post(route("file.store"));
};

//HOOKS
onMounted(() => {
    emitter.on(FILE_UPLOADED, uploadFiles);
});
</script>

<template>
    <div class="flex h-screen w-full gap-4 bg-gray-50">
        <Navigation />

        <main class="flex flex-1 flex-col overflow-hidden px-4">
            <div class="flex w-full items-center justify-between">
                <SearchForm />
                <UserSettingsDropdown />
            </div>

            <div
                class="mt-8 flex flex-1 flex-col overflow-hidden"
                @drop.prevent="handDrop"
                @dragover.prevent="handDragOver"
                @dragleave.prevent="handDragLeave"
            >
                <div
                    v-if="isDragOver"
                    class="flex h-32 w-full cursor-pointer appearance-none justify-center rounded-md border-2 border-dashed border-gray-300 bg-white px-4 transition hover:border-gray-400 focus:outline-none"
                >
                    <span class="flex items-center space-x-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-gray-600"
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
