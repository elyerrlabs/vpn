<template>
  <v-general-layout>
    <div class="space-y-6 p-4 md:p-6 transition-colors duration-200">
      <!-- Header -->
      <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
      >
        <div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white">
            {{ __("Server Administrator") }}
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{ __("Manage your server configurations") }}
          </p>
        </div>
        <div class="flex items-center gap-3">
          <v-create @created="getServers" />
        </div>
      </div>

      <!-- Search and Filter Section -->
      <div
        class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4"
      >
        <div class="flex flex-col md:flex-row md:items-center gap-4">
          <!-- Search Input -->
          <div class="flex-1">
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <svg
                  class="h-5 w-5 text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </div>
              <input
                v-model="search.name"
                type="text"
                :placeholder="__('Search by name or country...')"
                class="pl-10 pr-4 py-2.5 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors duration-200"
                @keyup.enter="getServers"
                @input="debounceSearch"
              />
            </div>
          </div>

          <!-- Filters -->
          <div class="flex flex-wrap gap-3">
            <!-- Internal Filter -->
            <div class="flex items-center">
              <label class="relative inline-flex items-center cursor-pointer">
                <input
                  v-model="search.internal"
                  type="checkbox"
                  class="sr-only peer"
                  @change="getServers"
                />
                <div
                  class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                ></div>
                <span
                  class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  {{ __("Internal") }}
                </span>
              </label>
            </div>

            <!-- Hidden Filter -->
            <div class="flex items-center">
              <label class="relative inline-flex items-center cursor-pointer">
                <input
                  v-model="search.hidden"
                  type="checkbox"
                  class="sr-only peer"
                  @change="getServers"
                />
                <div
                  class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                ></div>
                <span
                  class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  {{ __("Hidden") }}
                </span>
              </label>
            </div>

            <!-- Clear Filters Button -->
            <button
              @click="clearFilters"
              class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200 flex items-center gap-2"
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
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
              {{ __("Clear") }}
            </button>

            <!-- Search Button (Mobile) -->
            <button
              @click="getServers"
              class="md:hidden px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200 flex items-center gap-2"
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
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                />
              </svg>
              {{ __("Search") }}
            </button>
          </div>
        </div>

        <!-- Active Filters Indicator -->
        <div
          v-if="hasActiveFilters"
          class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700"
        >
          <div class="flex items-center gap-2 text-sm">
            <span class="text-gray-500 dark:text-gray-400">
              {{ __("Active filters") }}:
            </span>
            <div class="flex flex-wrap gap-2">
              <span
                v-if="search.name"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs"
              >
                {{ __("Search") }}: "{{ search.name }}"
                <button
                  @click="
                    search.name = '';
                    getServers();
                  "
                  class="ml-1 text-blue-500 hover:text-blue-700"
                >
                  ×
                </button>
              </span>
              <span
                v-if="search.internal"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs"
              >
                {{ __("Internal") }}: {{ __("Yes") }}
                <button
                  @click="
                    search.internal = false;
                    getServers();
                  "
                  class="ml-1 text-blue-500 hover:text-blue-700"
                >
                  ×
                </button>
              </span>
              <span
                v-if="search.hidden"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs"
              >
                {{ __("Hidden") }}: {{ __("Yes") }}
                <button
                  @click="
                    search.hidden = false;
                    getServers();
                  "
                  class="ml-1 text-blue-500 hover:text-blue-700"
                >
                  ×
                </button>
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Results Summary -->
      <div
        v-if="servers.length > 0"
        class="text-sm text-gray-600 dark:text-gray-400"
      >
        {{ __("Showing") }} {{ servers.length }} {{ __("of") }}
        {{ pages.total || 0 }} {{ __("servers") }}
        <span v-if="hasActiveFilters"> ({{ __("filtered") }}) </span>
      </div>

      <!-- Mobile cards -->
      <div class="grid grid-cols-1 gap-4 md:hidden">
        <div
          v-for="server in servers"
          :key="server.id"
          class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow duration-200"
        >
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 rounded-full bg-green-500"></div>
              <span
                class="text-base font-semibold text-gray-900 dark:text-white"
              >
                {{ server.country }}
              </span>
            </div>
            <span
              class="text-xs px-2.5 py-1 rounded-full font-medium"
              :class="
                server.internal
                  ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                  : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
              "
            >
              {{ __("Internal") }}: {{ server.internal ? __("Yes") : __("No") }}
            </span>
          </div>

          <div class="space-y-2.5 text-sm">
            <div class="flex items-center gap-2">
              <span class="w-20 text-gray-500 dark:text-gray-400 font-medium"
                >{{ __("IP") }}:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                server.ip
              }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-20 text-gray-500 dark:text-gray-400 font-medium"
                >{{ __("Port") }}:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                server.port
              }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-20 text-gray-500 dark:text-gray-400 font-medium"
                >{{ __("SOCKS") }}:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                server.socks_port
              }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-20 text-gray-500 dark:text-gray-400 font-medium"
                >{{ __("Proxy") }}:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                server.proxy_port
              }}</span>
            </div>

            <div class="flex items-center gap-2">
              <span class="w-20 text-gray-500 dark:text-gray-400 font-medium"
                >{{ __("Hidden") }}:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                server.hidden ? __("Yes") : __("No")
              }}</span>
            </div>
          </div>

          <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
            <div class="text-sm text-gray-600 dark:text-gray-400">
              {{ server.user?.name }} {{ server.user?.last_name }}
            </div>
          </div>
        </div>
      </div>

      <!-- Desktop table -->
      <div
        v-if="servers.length > 0"
        class="hidden md:block overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm"
      >
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead
              class="bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-700 dark:text-gray-300"
            >
              <tr>
                <th class="px-6 py-4 font-semibold">{{ __("Country") }}</th>
                <th class="px-6 py-4 font-semibold">{{ __("IP Address") }}</th>
                <th class="px-6 py-4 font-semibold">{{ __("Port") }}</th>
                <th class="px-6 py-4 font-semibold">{{ __("SOCKS") }}</th>
                <th class="px-6 py-4 font-semibold">{{ __("Proxy") }}</th>
                <th class="px-6 py-4 font-semibold">{{ __("Internal") }}</th>
                <th class="px-6 py-4 font-semibold">{{ __("Hidden") }}</th>
                <th class="px-6 py-4 font-semibold">{{ __("Actions") }}</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
              <tr
                v-for="server in servers"
                :key="server.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    <span class="font-semibold text-gray-900 dark:text-white">
                      {{ server.name }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <code
                    class="font-mono text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded text-sm"
                  >
                    {{ server.ip }}
                  </code>
                </td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                  {{ server.port }}
                </td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                  {{ server.socks_port }}
                </td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                  {{ server.proxy_port }}
                </td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                    :class="
                      server.internal
                        ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                        : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                    "
                  >
                    {{ server.internal ? __("Yes") : __("No") }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                    :class="
                      server.hidden
                        ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                        : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                    "
                  >
                    {{ server.hidden ? __("Yes") : __("No") }}
                  </span>
                </td>
                <td class="px-6 py-4 flex gap-2">
                  <v-delete @deleted="getServers" :item="server" />
                  <v-create
                    @updated="getServers"
                    :item="server"
                    :title="__('Update Server')"
                    :button-name="__('Update')"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-6">
        <v-pagination
          v-model="search.page"
          :total-pages="pages.total_pages"
          @change="getServers"
        />
      </div>

      <!-- Empty state -->
      <div
        v-if="servers.length === 0 && !loading"
        class="text-center py-12 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800"
      >
        <div
          class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4"
        >
          <svg
            class="w-8 h-8 text-gray-400 dark:text-gray-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"
            />
          </svg>
        </div>
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
          {{
            hasActiveFilters
              ? __("No servers match your filters")
              : __("No servers found")
          }}
        </h4>
        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
          {{
            hasActiveFilters
              ? __("Try adjusting your search criteria or clear filters")
              : __("Get started by adding your first server configuration")
          }}
        </p>
        <button
          v-if="hasActiveFilters"
          @click="clearFilters"
          class="mt-4 px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
        >
          {{ __("Clear all filters") }}
        </button>
      </div>

      <!-- Loading State -->
      <div
        v-if="loading"
        class="text-center py-12 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800"
      >
        <div class="inline-flex items-center justify-center">
          <div
            class="w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"
          ></div>
          <span class="ml-3 text-gray-600 dark:text-gray-400">
            {{ __("Loading servers...") }}
          </span>
        </div>
      </div>
    </div>
  </v-general-layout>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import VGeneralLayout from "@vpn/components/VGeneralLayout.vue";
import VPagination from "@vpn/components/VPaginate.vue";
import { ref, onMounted, computed } from "vue";
import VCreate from "./VCreate.vue";
import VDelete from "./VDelete.vue";

const page = usePage();
const servers = ref([]);
const loading = ref(false);
let searchTimeout = null;

const pages = ref({
  total_pages: 0,
  total: 0,
});

const search = ref({
  per_page: 15,
  page: 1,
  name: "",
  internal: null,
  hidden: null,
});

const hasActiveFilters = computed(() => {
  return (
    search.value.name ||
    search.value.internal !== null ||
    search.value.hidden !== null
  );
});

onMounted(async () => {
  await getServers();
});

const debounceSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    search.value.page = 1;
    getServers();
  }, 500);
};

const getServers = async () => {
  loading.value = true;
  try {
    const params = { ...search.value };
    if (params.internal === null) delete params.internal;
    if (params.hidden === null) delete params.hidden;
    if (!params.name) delete params.name;

    const res = await $server.get(page.props.servers.index, {
      params,
    });
    if (res.status == 200) {
      const values = res.data;
      servers.value = values.data;
      pages.value = values.meta.pagination;
    }
  } catch (error) {
    if (error?.response?.data?.message) {
      $notify.error(error.response.data.message);
    }
  } finally {
    loading.value = false;
  }
};

const clearFilters = () => {
  search.value = {
    per_page: 15,
    page: 1,
    name: "",
    internal: null,
    hidden: null,
  };
  getServers();
};
</script>
