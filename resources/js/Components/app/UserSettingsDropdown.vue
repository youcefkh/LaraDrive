<template>
    <Dropdown :label="$page.props.auth.user.name">
        <template v-slot:icon>
            <ChevronDownIcon
                class="-mr-1 ml-2 h-5 w-5 text-indigo-700"
                aria-hidden="true"
            />
        </template>
        <template v-slot:items>
            <div class="px-1 py-1">
                <MenuItem v-slot="{ active }">
                    <ResponsiveNavLink
                        :href="route('profile.edit')"
                        :class="[
                            active
                                ? 'bg-gray-100 text-gray-900'
                                : 'text-gray-700',
                            'flex gap-2 items-center px-4 py-2 text-sm',
                        ]"
                    >
                    <UserCircleIcon
                            class="h-5 w-5"
                            aria-hidden="true"
                        /> Profile
                    </ResponsiveNavLink>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                    <ResponsiveNavLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                        :class="[
                            active
                                ? 'bg-gray-100 text-gray-900'
                                : 'text-gray-700',
                            'flex gap-2 items-center px-4 py-2 text-sm',
                        ]"
                    >
                    <ArrowRightOnRectangleIcon
                            class="h-5 w-5"
                            aria-hidden="true"
                        /> Logout
                    </ResponsiveNavLink>
                </MenuItem>
            </div>
        </template>
    </Dropdown>
</template>

<script setup>
import { MenuItem } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import Dropdown from "../Dropdown.vue";
import ResponsiveNavLink from "../ResponsiveNavLink.vue";
import { ArrowRightOnRectangleIcon, UserCircleIcon } from "@heroicons/vue/24/outline";
</script>
