<template>
  <v-general-layout>
    <v-head
      :title="__('Wireguard device manager')"
      :description="
        __('Manage connected Wireguard peers and their configurations')
      "
    >
      <template #actions>
        <v-create @created="getPeers" />
      </template>
    </v-head>

    <v-table
      :items="peers"
      :loading="loading"
      :per-page="search.per_page"
      :show-pagination="false"
      :empty-text="__('Try adjusting your filters or create a new devices')"
      empty-icon="mdi mdi-account-off-outline"
      loading-text="Loading devices..."
      table-class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
      thead-class="bg-gray-50 dark:bg-gray-700"
      tbody-class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
    >
      <template #head>
        <tr>
          <th
            v-for="(column, index) in columns"
            :key="index"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
          >
            {{ column }}
          </th>
        </tr>
      </template>

      <template #default="{ items }">
        <tr
          v-for="item in items"
          :key="item.id"
          class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50"
        >
          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div
                class="w-2 h-2 rounded-full"
                :class="item.mounted ? 'bg-green-500' : 'bg-gray-400'"
              ></div>
              <div class="min-w-0">
                <div
                  class="font-semibold text-gray-900 dark:text-white truncate"
                >
                  {{ item.name }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                  ID: {{ item.id.substring(0, 8) }}...
                </div>
              </div>
            </div>
          </td>

          <!-- Allowed IP -->
          <td class="px-6 py-4">
            <code
              class="font-mono text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded text-sm"
            >
              {{ item.allowed_ips }}
            </code>
          </td>

          <!-- Keepalive -->
          <td class="px-6 py-4">
            <div class="flex items-center gap-1">
              <span class="text-gray-700 dark:text-gray-300">
                {{ item.persistent_keepalive }}
              </span>
              <span class="text-xs text-gray-500 dark:text-gray-400">s</span>
            </div>
          </td>

          <!-- Status -->
          <td class="px-6 py-4">
            <span
              class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
              :class="
                item.mounted
                  ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                  : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'
              "
            >
              {{ item.mounted ? "Connected" : "Disconnected" }}
            </span>
          </td>

          <!-- Wireguard Interface -->
          <td class="px-6 py-4">
            <div v-if="item.wireguard" class="space-y-1">
              <div class="font-medium text-gray-900 dark:text-white truncate">
                {{ item.wireguard.name }}
              </div>
              <div class="text-xs text-gray-500 dark:text-gray-400">
                Port: {{ item.wireguard.listen_port }}
              </div>
            </div>
            <div v-else class="text-gray-400 dark:text-gray-500 text-sm">
              No interface
            </div>
          </td>

          <!-- Server -->
          <td class="px-6 py-4">
            <div v-if="item.wireguard">
              <span
                class="font-medium text-blue-600 dark:text-blue-400 truncate"
              >
                {{ item.wireguard.server_name }}
              </span>
            </div>
            <div v-else class="text-gray-400 dark:text-gray-500 text-sm">-</div>
          </td>

          <!-- Actions -->
          <td class="px-6 py-4">
            <div class="flex items-center gap-2">
              <v-delete :item="item" @deleted="getPeers" />
            </div>
          </td>
        </tr>
      </template>
    </v-table>

    <v-pagination
      v-model="search.page"
      :total-pages="pages.total_pages"
      @change="getPeers"
    />
  </v-general-layout>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import VGeneralLayout from "@vpn/components/VGeneralLayout.vue";
import VPagination from "@vpn/components/VPaginate.vue";
import VTable from "@vpn/components/VTable.vue";
import VHead from "@vpn/components/VHead.vue";
import VDelete from "./VDelete.vue";
import VCreate from "./VCreate.vue";
import { ref, onMounted } from "vue";

const page = usePage();

const columns = [
  "Peer Name",
  "Allowed IP",
  "Keepalive",
  "Status",
  "Wireguard Interface",
  "Server",
  "Actions",
];

const pages = ref({
  total_pages: 0,
});

const search = ref({
  per_page: 15,
  page: 1,
  name: "",
});

const peers = ref([]);

onMounted(async () => {
  await getPeers();
});

const getPeers = async () => {
  try {
    const res = await $server.get(page.props.api.peers);

    if (res.status == 200) {
      const values = res.data;
      peers.value = values.data;
      pages.value = values.meta.pagination;
    }
  } catch (error) {
    if (error?.response?.data?.message) {
      $notify.error(error.response.data.message);
    }
    console.log(error);
  }
};
</script>
