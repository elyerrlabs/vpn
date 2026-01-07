<template>
  <v-general-layout>
    <div class="space-y-6 p-4 md:p-6 transition-colors duration-200">
      <!-- Header -->
      <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
      >
        <div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white">
            {{ __("Wireguard Peers Manager") }}
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{
              __("Manage connected Wireguard peers and their configurations")
            }}
          </p>
        </div>
        <v-create @created="getPeers" />
      </div>

      <!-- Mobile cards -->
      <div class="grid grid-cols-1 gap-4 md:hidden">
        <div
          v-for="peer in peers"
          :key="peer.id"
          class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm hover:shadow-md transition-shadow duration-200"
        >
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
              <div
                class="w-2 h-2 rounded-full"
                :class="peer.mounted ? 'bg-green-500' : 'bg-gray-400'"
              ></div>
              <span
                class="text-base font-semibold text-gray-900 dark:text-white"
              >
                {{ peer.name }}
              </span>
            </div>
            <span
              class="text-xs px-2.5 py-1 rounded-full font-medium"
              :class="
                peer.mounted
                  ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                  : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'
              "
            >
              {{ peer.mounted ? "Connected" : "Disconnected" }}
            </span>
          </div>

          <div class="space-y-2.5 text-sm">
            <div class="flex items-center gap-2">
              <span class="w-24 text-gray-500 dark:text-gray-400 font-medium"
                >Allowed IP:</span
              >
              <span class="text-gray-900 dark:text-gray-100 font-mono">{{
                peer.allowed_ips
              }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-24 text-gray-500 dark:text-gray-400 font-medium"
                >Keepalive:</span
              >
              <span class="text-gray-900 dark:text-gray-100"
                >{{ peer.persistent_keepalive }}s</span
              >
            </div>
            <div class="flex items-center gap-2">
              <span class="w-24 text-gray-500 dark:text-gray-400 font-medium"
                >MTU:</span
              >
              <span class="text-gray-900 dark:text-gray-100">{{
                peer.mtu
              }}</span>
            </div>
          </div>

          <!-- Wireguard Interface Info -->
          <div
            v-if="peer.wireguard"
            class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700"
          >
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <span
                  class="text-xs font-medium text-gray-600 dark:text-gray-400"
                  >Wireguard Interface</span
                >
              </div>
              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-600 dark:text-gray-400"
                    >Name:</span
                  >
                  <span
                    class="text-sm font-medium text-gray-900 dark:text-white"
                    >{{ peer.wireguard.name }}</span
                  >
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-600 dark:text-gray-400"
                    >Port:</span
                  >
                  <span class="text-sm text-gray-900 dark:text-gray-100">{{
                    peer.wireguard.listen_port
                  }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-600 dark:text-gray-400"
                    >Server:</span
                  >
                  <span
                    class="text-sm font-medium text-blue-600 dark:text-blue-400"
                    >{{ peer.wireguard.server_name }}</span
                  >
                </div>
              </div>
            </div>
          </div>

          <!-- Public Key (collapsible) -->
          <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
            <details class="group">
              <summary
                class="flex items-center justify-between cursor-pointer list-none"
              >
                <span
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                  >Public Key</span
                >
                <svg
                  class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform"
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
              </summary>
              <div class="mt-2">
                <code
                  class="block text-xs font-mono text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 p-2 rounded break-all"
                >
                  {{ peer.public_key }}
                </code>
              </div>
            </details>
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
                <th class="px-6 py-4 font-semibold">Peer Name</th>
                <th class="px-6 py-4 font-semibold">Allowed IP</th>
                <th class="px-6 py-4 font-semibold">Keepalive</th>
                <th class="px-6 py-4 font-semibold">MTU</th>
                <th class="px-6 py-4 font-semibold">Status</th>
                <th class="px-6 py-4 font-semibold">Wireguard Interface</th>
                <th class="px-6 py-4 font-semibold">Server</th>
                <th class="px-6 py-4 font-semibold">Actions</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
              <tr
                v-for="peer in peers"
                :key="peer.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150"
              >
                <!-- Peer Name -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-2 h-2 rounded-full"
                      :class="peer.mounted ? 'bg-green-500' : 'bg-gray-400'"
                    ></div>
                    <div class="min-w-0">
                      <div
                        class="font-semibold text-gray-900 dark:text-white truncate"
                      >
                        {{ peer.name }}
                      </div>
                      <div
                        class="text-xs text-gray-500 dark:text-gray-400 truncate"
                      >
                        ID: {{ peer.id.substring(0, 8) }}...
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Allowed IP -->
                <td class="px-6 py-4">
                  <code
                    class="font-mono text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded text-sm"
                  >
                    {{ peer.allowed_ips }}
                  </code>
                </td>

                <!-- Keepalive -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-1">
                    <span class="text-gray-700 dark:text-gray-300">
                      {{ peer.persistent_keepalive }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400"
                      >s</span
                    >
                  </div>
                </td>

                <!-- MTU -->
                <td class="px-6 py-4">
                  <div
                    class="inline-flex items-center justify-center min-w-[3rem] px-2 py-1 rounded text-xs font-medium"
                    :class="
                      peer.mtu >= 1400
                        ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                        : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'
                    "
                  >
                    {{ peer.mtu }}
                  </div>
                </td>

                <!-- Status -->
                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                    :class="
                      peer.mounted
                        ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                        : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'
                    "
                  >
                    {{ peer.mounted ? "Connected" : "Disconnected" }}
                  </span>
                </td>

                <!-- Wireguard Interface -->
                <td class="px-6 py-4">
                  <div v-if="peer.wireguard" class="space-y-1">
                    <div
                      class="font-medium text-gray-900 dark:text-white truncate"
                    >
                      {{ peer.wireguard.name }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                      Port: {{ peer.wireguard.listen_port }}
                    </div>
                  </div>
                  <div v-else class="text-gray-400 dark:text-gray-500 text-sm">
                    No interface
                  </div>
                </td>

                <!-- Server -->
                <td class="px-6 py-4">
                  <div v-if="peer.wireguard">
                    <span
                      class="font-medium text-blue-600 dark:text-blue-400 truncate"
                    >
                      {{ peer.wireguard.server_name }}
                    </span>
                  </div>
                  <div v-else class="text-gray-400 dark:text-gray-500 text-sm">
                    -
                  </div>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <v-delete :item="peer" @deleted="getPeers" />
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
          @change="getPeers"
        />
      </div>

      <!-- Empty state -->
      <div
        v-if="peers.length === 0"
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
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-8.197a6 6 0 00-9 5.197"
            />
          </svg>
        </div>
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
          {{ __("No peers found") }}
        </h4>
        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
          {{ __("No Wireguard peers are currently configured") }}
        </p>
      </div>
    </div>
  </v-general-layout>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import VGeneralLayout from "@vpn/components/VGeneralLayout.vue";
import VPagination from "@vpn/components/VPaginate.vue";
import VDelete from "./VDelete.vue";
import VCreate from "./VCreate.vue";
import { ref, onMounted } from "vue";

const page = usePage();

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
    const res = await $server.get(page.props.routes.peers);

    if (res.status == 200) {
      const values = res.data;
      peers.value = values.data;
      pages.value = values.meta.pagination;
    }
  } catch (error) {
    if (error?.response?.data?.message) {
      $notify.error(error.response.data.message);
    }
  }
};
</script>
