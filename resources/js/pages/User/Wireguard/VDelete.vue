<template>
  <div>
    <button
      @click="openDeleteModal"
      class="px-4 py-2 text-sm font-medium cursor-pointer bg-red-500 text-white transition-colors duration-200"
    >
      {{ __("Delete") }}
    </button>

    <v-modal
      v-model="showModal"
      :title="__('Delete Server')"
      panel-class="w-full lg:w-2xl"
    >
      <template #body>
        <div class="space-y-6">
          <!-- Warning Icon -->
          <div class="flex flex-col items-center text-center py-4">
            <div
              class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4"
            >
              <svg
                class="w-8 h-8 text-red-600 dark:text-red-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"
                />
              </svg>
            </div>

            <h3
              class="text-lg font-semibold text-gray-900 dark:text-white mb-2"
            >
              {{ __("Are you sure you want to delete this server?") }}
            </h3>

            <p class="text-gray-600 dark:text-gray-400 mb-6">
              {{
                __(
                  "This action cannot be undone. This will permanently delete the server configuration and all associated data."
                )
              }}
            </p>
          </div>

          <!-- Server Details Card -->
          <div
            class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4"
          >
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                  >{{ __("Server ID") }}</span
                >
                <code
                  class="text-sm font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-900 dark:text-gray-100"
                >
                  {{ serverId }}
                </code>
              </div>

              <div class="flex items-center justify-between">
                <span
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                  >{{ __("Name") }}</span
                >
                <span
                  class="text-sm font-semibold text-gray-900 dark:text-white"
                  >{{ item.name }}</span
                >
              </div>

              <div class="flex items-center justify-between">
                <span
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                  >{{ __("IP Address") }}</span
                >
                <span
                  class="text-sm text-gray-900 dark:text-gray-100 font-mono"
                  >{{ item.ip }}</span
                >
              </div>

              <div class="flex items-center justify-between">
                <span
                  class="text-sm font-medium text-gray-700 dark:text-gray-300"
                  >{{ __("Type") }}</span
                >
                <span
                  class="text-xs px-2.5 py-1 rounded-full font-medium"
                  :class="
                    item.internal
                      ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                      : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                  "
                >
                  {{ item.internal ? __("Internal") : __("Public") }}
                </span>
              </div>
            </div>
          </div>

          <!-- Confirmation Input -->
          <div class="space-y-3">
            <label
              for="confirm-delete"
              class="block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
              {{ __("To confirm, type the server ID below:") }}
            </label>
            <div class="flex items-center gap-3">
              <div class="flex-1">
                <input
                  id="confirm-delete"
                  v-model="confirmationText"
                  type="text"
                  :placeholder="__('Enter server ID')"
                  class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200"
                  @keyup.enter="deleteServer"
                />
              </div>
              <div class="text-sm font-mono text-gray-500 dark:text-gray-400">
                {{ serverId }}
              </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400">
              {{ __("This helps prevent accidental deletions.") }}
            </p>
          </div>

          <!-- Action Buttons -->
          <div
            class="flex flex-col-reverse sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-700"
          >
            <button
              @click="closeModal"
              class="flex-1 px-4 py-3 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
            >
              {{ __("Cancel") }}
            </button>

            <button
              @click="deleteServer"
              :disabled="!isConfirmed"
              class="flex-1 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
              :class="
                isConfirmed
                  ? 'bg-red-600 hover:bg-red-700 text-white cursor-pointer'
                  : 'bg-red-100 dark:bg-red-900/20 text-red-400 dark:text-red-500 cursor-not-allowed'
              "
            >
              <svg
                v-if="isDeleting"
                class="w-4 h-4 animate-spin"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                />
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                />
              </svg>
              <svg
                v-else
                class="w-4 h-4"
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
              {{ isDeleting ? __("Deleting...") : __("Delete Server") }}
            </button>
          </div>
        </div>
      </template>
    </v-modal>
  </div>
</template>

<script setup>
import VModal from "@vpn/components/VModal.vue";
import { ref, computed, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
  item: {
    type: Object,
    required: true,
    default: () => ({}),
  },
});

const emits = defineEmits(["deleted"]);

const page = usePage();
const showModal = ref(false);
const confirmationText = ref("");
const isDeleting = ref(false);

// Extract server ID from item
const serverId = computed(() => {
  return props.item.id || props.item.server_id;
});

// Check if confirmation text matches server ID
const isConfirmed = computed(() => {
  return confirmationText.value.trim() === serverId.value.toString();
});

const openDeleteModal = () => {
  confirmationText.value = "";
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  confirmationText.value = "";
  isDeleting.value = false;
};

const deleteServer = async () => {
  if (!isConfirmed.value || isDeleting.value) return;

  isDeleting.value = true;

  try {
    const response = await $server.delete(props.item.links.destroy, {
      params: { id: props.item.id },
    });

    if (response.status === 200) {
      $notify.success(__("Server deleted successfully"));
      emits("deleted");
      closeModal();
    }
  } catch (error) {
    if (error?.response?.data?.message) {
      $notify.error(error.response.data.message);
    }

    isDeleting.value = false;
  }
};

watch(showModal, (newVal) => {
  if (!newVal) {
    confirmationText.value = "";
    isDeleting.value = false;
  }
});
</script>
