<template>
  <div>
    <button
      @click="openDeleteModal"
      class="px-4 py-2 text-sm font-medium cursor-pointer bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 flex items-center gap-2"
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
          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
        />
      </svg>
      {{ __("Delete Device") }}
    </button>

    <v-modal
      v-model="showModal"
      :title="__('Delete Device')"
      panel-class="w-full lg:w-2xl"
    >
      <template #body>
        <div class="space-y-6">
          <!-- Warning Section -->
          <div
            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-5"
          >
            <div class="flex items-start gap-4">
              <div class="shrink-0">
                <div
                  class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-800 flex items-center justify-center"
                >
                  <svg
                    class="w-5 h-5 text-red-600 dark:text-red-400"
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
              </div>
              <div>
                <h3
                  class="font-bold text-red-800 dark:text-red-300 text-lg mb-2"
                >
                  {{ __("⚠️ Important: Read This First!") }}
                </h3>
                <ul class="space-y-2 text-red-700 dark:text-red-400 text-sm">
                  <li class="flex items-start gap-2">
                    <span
                      class="inline-block w-6 h-6 bg-red-100 dark:bg-red-800 rounded-full text-red-600 dark:text-red-400 text-xs flex items-center justify-center mt-0.5"
                      >①</span
                    >
                    <span
                      ><strong>{{ __("VPN will stop working") }}</strong>
                      {{ __("on all devices using this configuration") }}</span
                    >
                  </li>
                  <li class="flex items-start gap-2">
                    <span
                      class="inline-block w-6 h-6 bg-red-100 dark:bg-red-800 rounded-full text-red-600 dark:text-red-400 text-xs flex items-center justify-center mt-0.5"
                      >②</span
                    >
                    <span
                      ><strong>{{
                        __("You will lose internet access")
                      }}</strong>
                      {{
                        __("through VPN until you create a new device")
                      }}</span
                    >
                  </li>
                  <li class="flex items-start gap-2">
                    <span
                      class="inline-block w-6 h-6 bg-red-100 dark:bg-red-800 rounded-full text-red-600 dark:text-red-400 text-xs flex items-center justify-center mt-0.5"
                      >③</span
                    >
                    <span
                      ><strong>{{ __("Cannot be undone") }}</strong>
                      {{
                        __("- You'll need to set up again from scratch")
                      }}</span
                    >
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Device Information -->
          <div
            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5"
          >
            <h4
              class="font-semibold text-blue-800 dark:text-blue-300 text-sm uppercase tracking-wider mb-3"
            >
              {{ __("Device You're Deleting") }}
            </h4>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-gray-600 dark:text-gray-400 font-medium">{{
                  __("Device Name")
                }}</span>
                <span class="text-gray-900 dark:text-white font-bold text-lg">{{
                  item.name
                }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-600 dark:text-gray-400 font-medium">{{
                  __("VPN IP Address")
                }}</span>
                <span
                  class="text-gray-900 dark:text-white font-mono font-medium"
                  >{{ item.allowed_ips }}</span
                >
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-600 dark:text-gray-400 font-medium">{{
                  __("Created On")
                }}</span>
                <span class="text-gray-900 dark:text-white">
                  {{ item.created }}
                </span>
              </div>
            </div>
          </div>

          <!-- Simple Confirmation -->
          <div
            class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-5"
          >
            <div class="flex items-center gap-3 mb-4">
              <div class="shrink-0">
                <div
                  class="w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-800 flex items-center justify-center"
                >
                  <svg
                    class="w-4 h-4 text-yellow-600 dark:text-yellow-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </div>
              </div>
              <h4 class="font-semibold text-yellow-800 dark:text-yellow-300">
                {{ __("Do you want to continue?") }}
              </h4>
            </div>

            <div class="space-y-3">
              <label class="flex items-center gap-3 cursor-pointer">
                <input
                  type="checkbox"
                  v-model="confirmUnderstand"
                  class="w-5 h-5 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                />
                <span class="text-gray-700 dark:text-gray-300 font-medium">
                  {{ __("I understand my VPN will stop working") }}
                </span>
              </label>

              <label class="flex items-center gap-3 cursor-pointer">
                <input
                  type="checkbox"
                  v-model="confirmNewSetup"
                  class="w-5 h-5 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                />
                <span class="text-gray-700 dark:text-gray-300 font-medium">
                  {{ __("I know I'll need to set up again from scratch") }}
                </span>
              </label>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
            <button
              @click="closeModal"
              class="flex-1 px-6 py-3.5 text-base font-medium rounded-xl border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 flex items-center justify-center gap-2"
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
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
              {{ __("No, Keep Device") }}
            </button>

            <button
              @click="deleteDevice"
              :disabled="!canDelete || isDeleting"
              class="flex-1 px-6 py-3.5 text-base font-medium rounded-xl transition-all duration-200 flex items-center justify-center gap-2"
              :class="
                canDelete && !isDeleting
                  ? 'bg-red-600 hover:bg-red-700 text-white border-2 border-red-600 hover:border-red-700 cursor-pointer shadow-lg hover:shadow-red-200 dark:hover:shadow-red-900/30'
                  : 'bg-red-100 dark:bg-red-900/20 text-red-400 dark:text-red-500 border-2 border-red-100 dark:border-red-800 cursor-not-allowed'
              "
            >
              <svg
                v-if="isDeleting"
                class="w-5 h-5 animate-spin"
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
              {{ isDeleting ? __("Deleting...") : __("Yes, Delete Device") }}
            </button>
          </div>

          <!-- Final Warning -->
          <div class="text-center pt-2">
            <p class="text-xs text-gray-500 dark:text-gray-400">
              {{
                __(
                  "Tip: Create a new device first if you need uninterrupted VPN access",
                )
              }}
            </p>
          </div>
        </div>
      </template>
    </v-modal>
  </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import { ref, computed } from "vue";
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
const confirmUnderstand = ref(false);
const confirmNewSetup = ref(false);
const isDeleting = ref(false);

// Check if both confirmations are checked
const canDelete = computed(() => {
  return confirmUnderstand.value && confirmNewSetup.value;
});

const openDeleteModal = () => {
  confirmUnderstand.value = false;
  confirmNewSetup.value = false;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  confirmUnderstand.value = false;
  confirmNewSetup.value = false;
  isDeleting.value = false;
};

const deleteDevice = async () => {
  if (!canDelete.value || isDeleting.value) return;

  isDeleting.value = true;

  try {
    const response = await $server.delete(props.item.links.destroy);

    if (response.status === 200) {
      $notify.success(
        __("Device deleted"),
        __(
          "Your VPN configuration has been removed. Remember to delete it from your WireGuard app too.",
        ),
      );
      emits("deleted");
      closeModal();
    }
  } catch (error) {
    console.log(error);

    if (error?.response?.data?.message) {
      $notify.error(__("Delete failed"), error.response.data.message);
    } else {
      $notify.error(
        __("Delete failed"),
        __("Could not delete the device. Please try again."),
      );
    }

    isDeleting.value = false;
  }
};
</script>
