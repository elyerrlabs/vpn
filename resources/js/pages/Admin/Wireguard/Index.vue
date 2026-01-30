<template>
  <v-general-layout>
    <div class="p-2 transition-colors duration-200">
      <!-- Header -->
      <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-4"
      >
        <div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white">
            {{ __("Wireguard Interface Manager") }}
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{ __("Manage your Wireguard network interfaces") }}
          </p>
        </div>
        <div class="flex items-center gap-3">
          <v-create @created="getWireguardInterfaces" />
        </div>
      </div>

      <!-- Filter Toggle Button -->
      <div class="mb-4 flex items-center justify-between">
        <button
          @click="showFilters = !showFilters"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
        >
          <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
            />
          </svg>
          {{ __("Filters") }}
          <svg
            class="w-4 h-4 transition-transform duration-200"
            :class="{ 'rotate-180': showFilters }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 9l-7 7-7-7"
            />
          </svg>
        </button>

        <!-- Clear Filters Button (visible when filters are active) -->
        <button
          v-if="hasActiveFilters"
          @click="clearFilters"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200"
        >
          <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
          </svg>
          {{ __("Clear Filters") }}
        </button>
      </div>

      <!-- Filters Panel -->
      <div
        v-show="showFilters"
        class="mb-6 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm transition-all duration-300 ease-in-out"
      >
        <div
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
        >
          <!-- Name Filter -->

          <v-input
            v-model="search.name"
            :label="__('Name')"
            @input="getWireguardInterfaces"
            :placeholder="__('Search by name...')"
          />

          <!-- Server Filter -->
          <v-select
            :label="__('Servers')"
            v-model="search.server_id"
            :options="servers"
            searchable
            @search="getServers"
            @change="getWireguardInterfaces"
            class="w-full"
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

          <v-switch
            v-model="search.internal"
            :label="__('Internal')"
            @change="getWireguardInterfaces"
          />

          <v-switch
            v-model="search.hidden"
            :label="__('Hidden')"
            @change="getWireguardInterfaces"
          />

          <v-switch
            v-model="search.mounted"
            :label="__('Mounted')"
            @change="getWireguardInterfaces"
          />

          <v-switch
            v-model="search.public"
            :label="__('Public')"
            @change="getWireguardInterfaces"
          />
        </div>

        <!-- Active Filters Badges -->
        <div
          v-if="hasActiveFilters"
          class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700"
        >
          <div class="flex items-center gap-2 flex-wrap">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
              {{ __("Active filters:") }}
            </span>

            <template v-if="search.name">
              <span
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400"
              >
                {{ __("Name") }}: {{ search.name }}
                <button
                  @click="
                    search.name = '';
                    getWireguardInterfaces();
                  "
                  class="hover:text-blue-900 dark:hover:text-blue-300"
                >
                  <svg
                    class="w-3 h-3"
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
                </button>
              </span>
            </template>

            <template v-if="search.server_id">
              <span
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400"
              >
                {{ __("Server") }}: {{ getServerName(search.server_id) }}
                <button
                  @click="
                    search.server_id = null;
                    getWireguardInterfaces();
                  "
                  class="hover:text-purple-900 dark:hover:text-purple-300"
                >
                  <svg
                    class="w-3 h-3"
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
                </button>
              </span>
            </template>

            <template v-if="search.internal !== null">
              <span
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400"
              >
                {{ __("Internal") }}:
                {{ search.internal ? __("Yes") : __("No") }}
                <button
                  @click="
                    search.internal = null;
                    getWireguardInterfaces();
                  "
                  class="hover:text-green-900 dark:hover:text-green-300"
                >
                  <svg
                    class="w-3 h-3"
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
                </button>
              </span>
            </template>

            <template v-if="search.hidden !== null">
              <span
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400"
              >
                {{ __("Hidden") }}: {{ search.hidden ? __("Yes") : __("No") }}
                <button
                  @click="
                    search.hidden = null;
                    getWireguardInterfaces();
                  "
                  class="hover:text-yellow-900 dark:hover:text-yellow-300"
                >
                  <svg
                    class="w-3 h-3"
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
                </button>
              </span>
            </template>

            <template v-if="search.mounted !== null">
              <span
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400"
              >
                {{ __("Mounted") }}: {{ search.mounted ? __("Yes") : __("No") }}
                <button
                  @click="
                    search.mounted = null;
                    getWireguardInterfaces();
                  "
                  class="hover:text-indigo-900 dark:hover:text-indigo-300"
                >
                  <svg
                    class="w-3 h-3"
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
                </button>
              </span>
            </template>

            <template v-if="search.public !== null">
              <span
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400"
              >
                {{ __("Public") }}: {{ search.public ? __("Yes") : __("No") }}
                <button
                  @click="
                    search.public = null;
                    getWireguardInterfaces();
                  "
                  class="hover:text-pink-900 dark:hover:text-pink-300"
                >
                  <svg
                    class="w-3 h-3"
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
                </button>
              </span>
            </template>
          </div>
        </div>
      </div>

      <!-- Mobile cards -->
      <div class="grid grid-cols-1 gap-4 md:hidden">
        <div
          v-for="wg in wireguard"
          :key="wg.id"
          class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow duration-200"
        >
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
              <div
                class="w-2 h-2 rounded-full"
                :class="wg.mounted ? 'bg-green-500' : 'bg-gray-400'"
              ></div>
              <span
                class="text-base font-semibold text-gray-900 dark:text-white"
              >
                {{ wg.slug }}
              </span>
            </div>
            <div class="flex flex-col items-end gap-1">
              <span
                class="text-xs px-2.5 py-1 rounded-full font-medium"
                :class="
                  wg.public
                    ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                "
              >
                {{ wg.public ? "Public" : "Private" }}
              </span>
              <span
                v-if="wg.dns_enabled"
                class="text-xs px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full"
              >
                DNS
              </span>
            </div>
          </div>

          <div class="space-y-2.5 text-sm">
            <div class="flex items-center gap-2">
              <span class="w-24 text-gray-500 dark:text-gray-400 font-medium"
                >Subnet:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                wg.subnet
              }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-24 text-gray-500 dark:text-gray-400 font-medium"
                >Gateway:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                wg.gateway
              }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-24 text-gray-500 dark:text-gray-400 font-medium"
                >Listen Port:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                wg.listen_port
              }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-24 text-gray-500 dark:text-gray-400 font-medium"
                >Interface:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                wg.network_interface
              }}</span>
            </div>
          </div>

          <!-- Server Info -->
          <div
            v-if="wg.server"
            class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700"
          >
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-medium text-gray-600 dark:text-gray-400"
                >Server</span
              >
              <span
                class="text-xs px-2 py-0.5 rounded"
                :class="
                  wg.mounted
                    ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                    : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'
                "
              >
                {{ wg.mounted ? "Mounted" : "Not Mounted" }}
              </span>
            </div>
            <div class="flex items-center gap-2">
              <span
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                >{{ wg.server.name }}</span
              >
              <span class="text-xs text-gray-500 dark:text-gray-400">•</span>
              <span class="text-sm text-gray-600 dark:text-gray-400">{{
                wg.server.ip
              }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Desktop table -->
      <div
        class="hidden md:block overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm"
      >
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead
              class="bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-700 dark:text-gray-300"
            >
              <tr>
                <th class="px-6 py-4 font-semibold">Interface</th>
                <th class="px-6 py-4 font-semibold">Subnet</th>
                <th class="px-6 py-4 font-semibold">Gateway</th>
                <th class="px-6 py-4 font-semibold">Port</th>
                <th class="px-6 py-4 font-semibold">Status</th>
                <th class="px-6 py-4 font-semibold">Server</th>
                <th class="px-6 py-4 font-semibold">Actions</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
              <tr
                v-for="wg in wireguard"
                :key="wg.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150"
              >
                <!-- Interface Name -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-2 h-2 rounded-full"
                      :class="wg.mounted ? 'bg-green-500' : 'bg-gray-400'"
                    ></div>
                    <div class="min-w-0">
                      <div
                        class="font-semibold text-gray-900 dark:text-white truncate"
                      >
                        {{ wg.slug }}
                      </div>
                      <div
                        class="text-xs text-gray-500 dark:text-gray-400 truncate"
                      >
                        {{ wg.network_interface }}
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Subnet -->
                <td class="px-6 py-4">
                  <code
                    class="font-mono block text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded text-sm"
                  >
                    {{ wg.subnet }}
                  </code>
                  <code
                    v-show="wg.dns_enabled"
                    class="font-mono block mt-2 text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded text-sm"
                  >
                    DNS: {{ wg.dns }} <br />
                    Status: {{ wg.dns_enabled ? __("Yes") : __("No") }}
                  </code>
                </td>

                <!-- Gateway -->
                <td class="px-6 py-4">
                  <code
                    class="font-mono text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded text-sm"
                  >
                    {{ wg.gateway }}
                  </code>
                </td>

                <!-- Listen Port -->
                <td class="px-6 py-4">
                  <span class="font-medium text-gray-700 dark:text-gray-300">
                    {{ wg.listen_port }}
                  </span>
                </td>

                <!-- Status -->
                <td class="px-6 py-4">
                  <div class="flex flex-col gap-1">
                    <span
                      class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                      :class="
                        wg.public
                          ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                          : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                      "
                    >
                      {{ wg.public ? "Public" : "Private" }}
                    </span>
                    <span
                      class="text-xs px-2 py-0.5 rounded-full font-medium"
                      :class="
                        wg.mounted
                          ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                          : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'
                      "
                    >
                      {{ wg.mounted ? "Mounted" : "Not Mounted" }}
                    </span>
                  </div>
                </td>

                <!-- Server Info -->
                <td class="px-6 py-4">
                  <div v-if="wg.server" class="space-y-1">
                    <div
                      class="font-medium text-gray-900 dark:text-white truncate"
                    >
                      {{ wg.server.name }}
                    </div>
                    <div class="flex items-center gap-2">
                      <code
                        class="text-xs font-mono text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 px-1.5 py-0.5 rounded"
                      >
                        {{ wg.server.ip }}
                      </code>
                      <a
                        v-if="wg.server.url"
                        :href="wg.server.url"
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

                <!-- Actions -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <v-delete @deleted="getWireguardInterfaces" :item="wg" />
                    <v-create
                      @updated="getWireguardInterfaces"
                      :item="wg"
                      :title="__('Update Interface')"
                      :button-name="__('Edit')"
                    />
                    <v-actions :item="wg" @updated="getWireguardInterfaces" />
                  </div>
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
          @change="getWireguardInterfaces"
        />
      </div>

      <!-- Empty state -->
      <div
        v-if="wireguard.length === 0"
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
              d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
            />
          </svg>
        </div>
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
          {{ __("No Wireguard interfaces found") }}
        </h4>
        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
          {{ __("Get started by creating your first Wireguard wg") }}
        </p>
        <div v-if="hasActiveFilters" class="mt-4">
          <button
            @click="clearFilters"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200"
          >
            {{ __("Clear filters to see all interfaces") }}
          </button>
        </div>
      </div>
    </div>
  </v-general-layout>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import VGeneralLayout from "@vpn/components/VGeneralLayout.vue";
import VPagination from "@vpn/components/VPaginate.vue";
import VInput from "@vpn/components/VInput.vue";
import VSwitch from "@vpn/components/VSwitch.vue";
import VSelect from "@vpn/components/VSelect.vue";
import { ref, onMounted, computed } from "vue";
import VCreate from "./VCreate.vue";
import VDelete from "./VDelete.vue";
import VActions from "./Actions.vue";

const page = usePage();
const wireguard = ref([]);
const showFilters = ref(true);

const pages = ref({
  total_pages: 0,
});

const search = ref({
  per_page: 15,
  page: 1,
  name: "",
  internal: true,
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

const hasActiveFilters = computed(() => {
  return (
    search.value.name !== "" ||
    search.value.server_id !== null ||
    search.value.internal !== null ||
    search.value.hidden !== null ||
    search.value.mounted !== null ||
    search.value.public !== null
  );
});

onMounted(async () => {
  await getWireguardInterfaces();
  await getServers();
});

const getWireguardInterfaces = async () => {
  try {
    const res = await $server.get(page.props.wireguard.index, {
      params: search.value,
    });

    if (res.status === 200) {
      wireguard.value = res.data.data;
      pages.value = res.data.meta.pagination;
    }
  } catch (error) {
    if (error?.response?.data?.message) {
      $notify.error(error.response.data.message);
    }
  }
};

const getServers = async () => {
  try {
    const res = await $server.get(page.props.wireguard.servers, {
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
  search.value = {
    per_page: 15,
    page: 1,
    name: "",
    internal: null,
    hidden: null,
    mounted: null,
    public: null,
    server_id: null,
  };
  getWireguardInterfaces();
};

// Obtener nombre del servidor por ID
const getServerName = (serverId) => {
  const server = servers.value.find((s) => s.id === serverId);
  return server ? server.name : __("Unknown server");
};
</script>
