<script setup>
//IMPORTS
import { HomeIcon } from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";

//PROPS AND EMITS
const props = defineProps({
    folders: Object,
});
</script>

<template>
    <nav aria-label="Breadcrumb">
        <ol class="flex items-center whitespace-nowrap">
            <li
                class="inline-flex items-center"
                v-for="(folder, index) in folders"
            >
                <Link
                    v-if="index == 0"
                    class="flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:focus:text-blue-500"
                    :href="route('myFiles', { folder: folder.path })"
                >
                    <HomeIcon class="h-5 w-5 mr-2" />
                    Home
                </Link>
                <Link
                    v-else
                    :class="
                        index == folders.length - 1
                            ? 'inline-flex items-center text-sm font-semibold text-gray-800 truncate dark:text-gray-200'
                            : 'flex items-center text-sm text-gray-500 hover:text-blue-600 focus:outline-none focus:text-blue-600 dark:focus:text-blue-500'
                    "
                    :href="route('myFiles', { folder: folder.path })"
                >
                    {{ folder.name }}
                </Link>
                <svg
                    v-if="index != folders.length - 1"
                    class="flex-shrink-0 mx-2 overflow-visible h-4 w-4 text-gray-400 dark:text-neutral-600 dark:text-neutral-600"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </li>
        </ol>
    </nav>
</template>
