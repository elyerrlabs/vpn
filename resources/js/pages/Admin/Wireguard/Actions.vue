<template>
  <div>
    <button
      @click="openModal"
      class="mx-4 py-2 px-4 text-white bg-blue-600 hover:bg-blue-700 cursor-pointer rounded transition-colors"
    >
      {{ __("Manage") }}
    </button>

    <v-modal v-model="dialog" :title="__('Make operations')">
      <template #body>
        <div class="p-4">
          <div class="mb-4">
            <p class="font-medium text-gray-800 dark:text-gray-200">
              {{ __("Server") }}: {{ item?.name }}
            </p>
            <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">
              {{ __("Current status") }}:
              <span :class="statusClass">
                {{ item?.mounted ? __("Mounted") : __("Not mounted") }}
              </span>
            </p>
          </div>

          <div class="flex flex-col space-y-3">
            <!-- Botón Start -->
            <button
              v-if="!item?.mounted"
              @click="handleStart"
              class="py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="processing"
            >
              <span v-if="processing" class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                    fill="none"
                  />
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  />
                </svg>
                {{ __("Processing...") }}
              </span>
              <span v-else>
                {{ __("Start Server") }}
              </span>
            </button>

            <!-- Botón Shutdown -->
            <button
              v-if="item?.mounted"
              @click="handleShutdown"
              class="py-2 px-4 bg-red-600 hover:bg-red-700 text-white rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="processing"
            >
              <span v-if="processing" class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                    fill="none"
                  />
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  />
                </svg>
                {{ __("Processing...") }}
              </span>
              <span v-else>
                {{ __("Shutdown Server") }}
              </span>
            </button>

            <!-- Mensajes informativos -->
            <div
              v-if="item?.mounted"
              class="mt-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded text-sm text-yellow-800 dark:text-yellow-300"
            >
              {{
                __(
                  "The server is currently running. You can shut it down if needed.",
                )
              }}
            </div>
            <div
              v-else
              class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded text-sm text-blue-800 dark:text-blue-300"
            >
              {{ __("The server is stopped. You can start it now.") }}
            </div>
          </div>
        </div>
      </template>
    </v-modal>
  </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import VModal from "@vpn/components/VModal.vue";

const page = usePage();

const props = defineProps({
  item: {
    type: Object,
    required: false,
    default: () => ({}),
  },
});

const emits = defineEmits(["updated"]);

const dialog = ref(false);
const processing = ref(false);

const statusClass = computed(() => {
  return props.item?.mounted
    ? "text-green-600 dark:text-green-400 font-medium"
    : "text-red-600 dark:text-red-400 font-medium";
});

const openModal = () => {
  dialog.value = true;
};

const handleStart = async () => {
  if (processing.value) return;

  processing.value = true;
  try {
    const res = await $server.put(props.item.links.start);

    if (res.status == 200) {
      $notify.success(__(res.data.message));
      dialog.value = false;
      emits("updated");
    }
  } catch (e) {
    if (e?.response?.data?.message) {
      $notify.error(e.response.data.message);
    } else {
      $notify.error(__("An error occurred while starting the server"));
    }
  } finally {
    processing.value = false;
  }
};

const handleShutdown = async () => {
  if (processing.value) return;

  processing.value = true;
  try {
    const res = await $server.put(props.item.links.shutdown);

    if (res.status == 200) {
      $notify.success(__(res.data.message));
      dialog.value = false;
      emits("updated");
    }
  } catch (e) {
    if (e?.response?.data?.message) {
      $notify.error(e.response.data.message);
    } else {
      $notify.error(__("An error occurred while shutting down the server"));
    }
  } finally {
    processing.value = false;
  }
};
</script>
