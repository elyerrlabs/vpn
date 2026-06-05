<template>
  <v-general-layout>
    <v-head
      :title="__('Wireguard Interface Manager')"
      :description="__('Manage your Wireguard network interfaces')"
    >
      <template #actions>
        <v-create @created="getWireguardInterfaces" />
      </template>
      <template #bottom>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
          <v-input
            v-model="search.name"
            :label="__('Name')"
            :placeholder="__('Name ...')"
          />

          <!-- Server Filter -->
          <v-select
            :label="__('Servers')"
            v-model="search.server_id"
            :options="servers"
            searchable
            @search="getServers"
            @change="getWireguardInterfaces"
          >
            <!-- Selected -->
            <template #selected="{ option }">
              <div v-if="option" class="flex justify-between items-center">
                <span class="text-gray-900 dark:text-white">
                  {{ option.name }}
                </span>
                <span
                  class="text-xs text-gray-500 dark:text-gray-400 font-mono"
                >
                  {{ option.ip }}
                </span>
              </div>

              <span v-else class="text-gray-400">
                {{ __("All servers") }}
              </span>
            </template>

            <!-- Dropdown option -->
            <template #option="{ option }">
              <div
                class="px-4 py-3 rounded-lg transition-colors hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500"
              >
                <div class="flex items-center justify-between">
                  <div class="flex flex-col">
                    <span class="font-semibold">
                      {{ option.name }}
                    </span>
                    <span class="text-xs font-mono opacity-80">
                      {{ option.ip }}
                    </span>
                  </div>
                </div>

                <div v-if="!option.internal" class="mt-2 text-xs opacity-80">
                  {{ __("By") }}
                  {{ option.user?.name }}
                  {{ option.user?.last_name }}
                </div>
              </div>
            </template>
          </v-select>

          <v-select
            :label="__('Servers types')"
            v-model="search.internal"
            @change="getWireguardInterfaces"
            :options="[
              { name: 'All', id: '' },
              { name: 'Privates', id: true },
              { name: 'Public', id: false },
            ]"
          />

          <v-select
            :label="__('Server visibility')"
            v-model="search.hidden"
            @change="getWireguardInterfaces"
            :options="[
              { name: __('All'), id: '' },
              { name: __('Hidden'), id: true },
              { name: __('Visible'), id: false },
            ]"
          />

          <v-select
            :label="__('Server status')"
            v-model="search.hidden"
            @change="getWireguardInterfaces"
            :options="[
              { name: __('All'), id: '' },
              { name: __('Started'), id: 1 },
              { name: __('Shutdown'), id: 0 },
            ]"
          />

          <v-select
            :label="__('Pagination')"
            v-model="search.per_page"
            @change="getWireguardInterfaces"
            :options="[
              { name: 15, id: 15 },
              { name: 50, id: 50 },
              { name: 100, id: 100 },
              { name: 150, id: 150 },
              { name: 200, id: 200 },
              { name: 300, id: 300 },
              { name: 500, id: 500 },
            ]"
          />

          <div class="flex justify-around items-end">
            <v-button
              :label="__('Search')"
              @click="getWireguardInterfaces"
              variant="success"
            />
            <v-button
              :label="__('Reset')"
              @click="clearFilters"
              variant="secondary"
            />
          </div>
        </div>
      </template>
    </v-head>

    <v-table
      :items="wireguard"
      :loading="loading"
      :per-page="search.per_page"
      :show-pagination="false"
      :empty-text="__('Try adjusting your filters or create a new server')"
      empty-icon="mdi mdi-account-off-outline"
      loading-text="Loading servers..."
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
          <!-- Interface Name -->
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
                  {{ item.slug }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                  {{ item.network_interface }}
                </div>
              </div>
            </div>
          </td>

          <!-- Subnet -->
          <td class="px-6 py-4">
            <code
              class="font-mono block text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded text-sm"
            >
              {{ item.subnet }}
            </code>
            <code
              v-show="item.dns_enabled"
              class="font-mono block mt-2 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded text-sm"
            >
              DNS: {{ item.dns }} <br />
              Status: {{ item.dns_enabled ? __("Yes") : __("No") }}
            </code>
          </td>

          <!-- Gateway -->
          <td class="px-6 py-4">
            <code
              class="font-mono text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded text-sm"
            >
              {{ item.gateway }}
            </code>
          </td>

          <!-- Listen Port -->
          <td class="px-6 py-4">
            <span class="font-medium text-gray-700 dark:text-gray-300">
              {{ item.listen_port }}
            </span>
          </td>

          <!-- Status -->
          <td class="px-6 py-4">
            <div class="flex flex-col gap-1">
              <span
                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                :class="
                  item.public
                    ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                "
              >
                {{ item.public ? "Public" : "Private" }}
              </span>
              <span
                class="text-xs px-2 py-0.5 rounded-full font-medium"
                :class="
                  item.mounted
                    ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                    : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'
                "
              >
                {{ item.mounted ? "Mounted" : "Not Mounted" }}
              </span>
            </div>
          </td>

          <!-- Server Info -->
          <td class="px-6 py-4">
            <div v-if="item.server" class="space-y-1">
              <div class="font-medium text-gray-900 dark:text-white truncate">
                {{ item.server.name }}
              </div>
              <div class="flex items-center gap-2">
                <code
                  class="text-xs font-mono text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 px-1.5 py-0.5 rounded"
                >
                  {{ item.server.ip }}
                </code>
                <a
                  v-if="item.server.url"
                  :href="item.server.url"
                  target="_blank"
                  class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                >
                  <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                    />
                  </svg>
                </a>
              </div>
            </div>
            <div v-else class="text-gray-400 dark:text-gray-500 text-sm">
              No server
            </div>
          </td>
          <td class="flex justify-end gap-2">
            <v-create @updated="getWireguardInterfaces" :item="item" />
            <v-actions :item="item" @updated="getWireguardInterfaces" />
            <v-delete @deleted="getWireguardInterfaces" :item="item" />
          </td>
        </tr>
      </template>
    </v-table>

    <!-- Pagination -->
    <v-pagination
      v-model="search.page"
      :total-pages="pages.total_pages"
      @change="getWireguardInterfaces"
    />
  </v-general-layout>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import VGeneralLayout from "@/components/VGeneralLayout.vue";
import VPagination from "@/components/VPaginate.vue";
import VHead from "@/components/VHead.vue";
import VTable from "@/components/VTable.vue";
import VInput from "@/components/VInput.vue";
import VButton from "@/components/VButton.vue";
import VSwitch from "@/components/VSwitch.vue";
import VSelect from "@/components/VSelect.vue";
import { ref, onMounted, computed } from "vue";
import VCreate from "./VCreate.vue";
import VDelete from "./VDelete.vue";
import VActions from "./Actions.vue";
import { useForm } from "@inertiajs/vue3";

const page = usePage();

const wireguard = ref([]);
const loading = ref(false);
const pages = ref({
  total_pages: 0,
});

const columns = [
  "Interface",
  "Subnet",
  "Gateway",
  "Port",
  "Status",
  "Server",
  "Actions",
];

const search = useForm({
  per_page: 15,
  page: 1,
  name: "",
  internal: null,
  hidden: null,
  mounted: null,
  public: null,
  server_id: null,
});

const servers = ref([]);
const search_server = ref({
  slug: "",
  per_page: 50,
  hidden: false,
});

onMounted(async () => {
  loadData(page.props.data);
  await getServers();
});

const loadData = (data) => {
  wireguard.value = data.data;
  pages.value = data.meta.pagination;
};

const getWireguardInterfaces = () => {
  search.get(page.props.routes.wireguard, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: (res) => {
      loadData(res.props.data);
    },
    onError: (e) => {
      console.log(e);
    },
  });
};

const getServers = async () => {
  try {
    const res = await $server.get(page.props.api.servers, {
      params: search_server.value,
    });
    if (res.status == 200) {
      servers.value = res.data.data;
    }
  } catch (error) {
    if (error?.response?.data?.message) {
      $notify.error(error.response.data.message);
    }
  }
};

const clearFilters = () => {
  search.resetAndClearErrors();
  getWireguardInterfaces();
};
</script>
