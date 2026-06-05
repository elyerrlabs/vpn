<template>
  <v-layout>
    <template #aside>
      <v-item-menu :items="page.props.menus" />
    </template>
    <template #main>
      <slot />
    </template>
  </v-layout>
</template>

<script setup>
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import { router, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const menus = ref([]);
const page = usePage();

onMounted(() => {
  menus.value = page.props.menus;
});

const open = (item) => {
  window.location.href = item.route;
};

const isActive = (item) => {
  if (!item?.route) return false;

  // Current query without params
  const currentPath = window.location.pathname;

  //Get only the path without query params
  const itemPath = new URL(item.route, window.location.origin).pathname;

  return currentPath === itemPath;
};
</script>
