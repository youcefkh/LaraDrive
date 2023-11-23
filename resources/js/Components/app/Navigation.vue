<script setup>
import NavLink from "../NavLink.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import CreateNewDropdown from "./CreateNewDropdown.vue";

import { Link, router } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const navLinks = [
    {
        name: "My Files",
        href: "myFiles",
    },
    {
        name: "Shared with me",
        href: "dashboard",
    },
    {
        name: "Shared by me",
        href: "dashboard",
    },
    {
        name: "Trash",
        href: "dashboard",
    },
];
</script>

<template>
    <nav class="min-w-[200px]">
        <div class="h-[80px] px-3 flex items-center gap-3">
            <Link :href="route('myFiles')" class="flex items-center gap-2">
                <ApplicationLogo
                    class="block h-9 w-auto fill-current text-gray-800"
                />
                <span>{{ page.props.app.name }}</span>
            </Link>
        </div>
        <div class="px-3">
            <CreateNewDropdown class="py-3" />
            <hr>
            <div class="py-3">
                <NavLink
                    v-for="link in navLinks"
                    :key="link.name"
                    :href="route(link.href)"
                    :active="link.href == route().current()"
                >
                    {{ link.name }}
                </NavLink>
            </div>
        </div>
    </nav>
</template>
