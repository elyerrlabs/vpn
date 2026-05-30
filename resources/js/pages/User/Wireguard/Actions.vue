<template>
  <div>
    <v-button
      @click="openModal"
      variant="warning"
      :title="__('Realod')"
      left-icon="mdi mdi-reload"
      round
    />

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
            <!-- Botón Shutdown -->
            <v-button
              v-if="item.mounted"
              @click="handleShutdown"
              :disabled="processing"
              left-icon="mdi mdi-power"
              variant="danger"
              :label="processing ? __('Processing...') : __('Shutdown Server')"
            />

            <v-button
              v-else
              @click="handleStart"
              :disabled="processing"
              :label="processing ? __('Processing...') : __('Start Server')"
              variant="success"
              left-icon="mdi mdi-power"
            />

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
import VButton from "@vpn/components/VButton.vue";
import { useForm } from "@inertiajs/vue3";

const page = usePage();

const props = defineProps({
  item: {
    type: Object,
    default: () => ({}),
  },
});

const emits = defineEmits(["updated"]);

const form = useForm({});
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

const handleStart = () => {
  if (processing.value) return;

  processing.value = true;

  form.put(props.item.links.start, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: (res) => {
      $notify.success(__("Server started successfully"));
      dialog.value = false;
      emits("updated");
    },
    onError: (e) => {
      console.log(e);
    },
    onFinish: () => {
      processing.value = false;
    },
  });
};

const handleShutdown = () => {
  if (processing.value) return;

  processing.value = true;

  form.put(props.item.links.shutdown, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: (res) => {
      $notify.success(__("Server shudown successfully"));
      dialog.value = false;
      emits("updated");
    },
    onError: (e) => {
      console.log(e);
    },
    onFinish: () => {
      processing.value = false;
    },
  });
};
</script>
