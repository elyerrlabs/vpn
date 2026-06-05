<template>
  <v-general-layout>
    <v-head
      :title="__('Server Administrator')"
      :descripction="__('Manage your server configurations')"
    >
      <template #actions>
        <v-create @created="getServers" />
      </template>
      <template #bottom>
        <div class="flex gap-2">
          <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-2">
            <v-input
              :label="__('Name')"
              v-model="search.name"
              @keyup.enter="getServers"
            />

            <v-select
              :label="__('Server visibility')"
              v-model="search.hidden"
              @change="getServers"
              :options="[
                { name: __('All'), id: '' },
                { name: __('Hidden'), id: 1 },
                { name: __('Visible'), id: 0 },
              ]"
            />

            <v-select
              :label="__('Pagination')"
              v-model="search.per_page"
              @change="getServers"
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
          </div>
          <div class="shrink-0 flex justify-end items-end gap-2">
            <v-button
              :label="__('Search')"
              @click="getServers"
              variant="success"
            />
            <v-button
              :label="__('clear')"
              @click="clearFilters"
              variant="secondary"
            />
          </div>
        </div>
      </template>
    </v-head>

    <v-table
      :items="servers"
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
          <td class="px-6 py-4">
            <div class="flex items-start gap-3">
              <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
              <div>
                <p class="font-semibold text-gray-900 dark:text-white">
                  {{ item.name }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Host: {{ item.ip }}:{{ item.port }}
                </p>
              </div>
            </div>
          </td>
          <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
            {{ item.socks_port }}
          </td>
          <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
            {{ item.proxy_port }}
          </td>

          <td class="px-6 py-4">
            <span
              class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
              :class="
                item.hidden
                  ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                  : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
              "
            >
              {{ item.hidden ? __("Yes") : __("No") }}
            </span>
          </td>
          <td class="px-6 py-4 text-right">
            <div class="flex justify-end gap-2">
              <v-create @updated="getServers" :item="item" />
              <v-delete @deleted="getServers" :item="item" />
            </div>
          </td>
        </tr>
      </template>
    </v-table>

    <!-- Pagination -->
    <div class="mt-6">
      <v-pagination
        v-model="search.page"
        :total-pages="pages.total_pages"
        @change="getServers"
      />
    </div>
  </v-general-layout>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import VGeneralLayout from "@/components/VGeneralLayout.vue";
import VHead from "@/components/VHead.vue";
import VInput from "@/components/VInput.vue";
import VButton from "@/components/VButton.vue";
import VSelect from "@/components/VSelect.vue";
import VTable from "@/components/VTable.vue";
import VPagination from "@/components/VPaginate.vue";
import { ref, onMounted, computed } from "vue";
import VCreate from "./VCreate.vue";
import VDelete from "./VDelete.vue";
import { useForm } from "@inertiajs/vue3";

const page = usePage();
const servers = ref([]);
const loading = ref(false);

const columns = ref(["Country", "SOCKS", "Proxy", "Hidden", "Actions"]);
const pages = ref({
  total_pages: 0,
  total: 0,
});

const search = useForm({
  name: "",
  per_page: 15,
  page: 1,
  hidden: null,
});

onMounted(() => {
  loadData(page.props.data);
});

const loadData = (data) => {
  servers.value = data.data;
  pages.value = data.meta.pagination;
};

const getServers = () => {
  loading.value = true;

  search.get(page.props.routes.servers, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: (res) => {
      loadData(res.props.data);
    },
    onError: (e) => {
      console.log(e);
    },
    onFinish: () => {
      loading.value = false;
    },
  });
};

const clearFilters = () => {
  search.resetAndClearErrors();
  getServers();
};
</script>
