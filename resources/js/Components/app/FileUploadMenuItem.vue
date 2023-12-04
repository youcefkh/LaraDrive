<script setup>
import { MenuItem } from "@headlessui/vue";
import { DocumentIcon } from "@heroicons/vue/24/outline";
import { emitter, FILE_UPLOADED } from "@/event-bus.js";

//METHODS
const uploadFiles = (e) => {
    emitter.emit(FILE_UPLOADED, e.target.files);
};
</script>

<template>
    <MenuItem>
        <button
            class="w-full border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-indigo-50 hover:border-indigo-100 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
        >
            <label
                for="file-upload"
                class="flex gap-2 items-center w-full cursor-pointer ps-3 pe-4 py-2"
            >
                <DocumentIcon class="h-5 w-5" aria-hidden="true" /> Upload Files
            </label>
            <input
                type="file"
                name="file-upload"
                id="file-upload"
                class="hidden"
                @change="uploadFiles"
                multiple
            />
        </button>
    </MenuItem>
</template>
