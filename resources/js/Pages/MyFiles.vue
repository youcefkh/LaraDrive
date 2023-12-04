<script setup>
//IMPORTS
import { onMounted, ref } from "vue";
import { FolderIcon } from "@heroicons/vue/24/outline";
import AuthenticatedLayout from "../Layouts/AuthenticatedLayout.vue";
import Breadcrumbs from "../Components/app/Breadcrumbs.vue";
import { computed } from "@vue/reactivity";
import { router } from "@inertiajs/core";
import { Link } from "@inertiajs/vue3";

//PROPS AND EMITS
//from the controller
const props = defineProps({
    files: Object,
    ancestors: Object,
    folder: Object,
});

//COMPUTED
const allFiles = computed(() => {
    return {
        data: props.files.data,
        next: props.files.links.next,
    };
});

//METHODS
const openFolder = (file) => {
    if (!file.is_folder) return;

    router.visit(route("myFiles", { folder: file.path }));
};
</script>

<template>
    <AuthenticatedLayout>
        <Breadcrumbs :folders="ancestors.data" class="my-5"/>

        <div class="flex-1 overflow-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th
                            class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                        >
                            Name
                        </th>
                        <th
                            class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                        >
                            Owner
                        </th>
                        <th
                            class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                        >
                            Last Modified
                        </th>
                        <th
                            class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                        >
                            Size
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        @dblclick="openFolder(file)"
                        v-for="file of allFiles.data"
                        :key="file.id"
                        class="border-b transition duration-300 ease-in-out hover:bg-blue-100 cursor-pointer"
                    >
                        <td
                            class="flex items-center gap-2 px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex items-center"
                        >
                            <FolderIcon class="h-5 w-5" v-if="file.is_folder" />
                            {{ file.name }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                        >
                            {{ file.owner }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                        >
                            {{ file.updated_at }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                        >
                            {{ file.size }}
                        </td>
                    </tr>
                    <tr v-if="allFiles.data.length === 0">
                        <td
                            colspan="5"
                            class="text-center py-3 font-semibold text-gray-500"
                        >
                            This folder is empty
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
