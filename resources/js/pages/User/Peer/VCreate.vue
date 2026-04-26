<template>
  <div>
    <button
      @click="open"
      class="px-4 py-2 cursor-pointer bg-blue-500 hover:bg-blue-600 text-white dark:bg-blue-600 dark:hover:bg-blue-700 rounded-md transition-colors flex items-center gap-2"
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
          d="M12 4v16m8-8H4"
        />
      </svg>
      {{ __("Create new device") }}
    </button>
    <v-modal
      v-model="dialog"
      :title="title"
      panel-class="w-full lg:w-4xl"
      @close="resetModal"
    >
      <template #body>
        <div v-if="!createdPeer">
          <div
            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6"
          >
            <div class="flex items-start gap-3">
              <div class="shrink-0">
                <div
                  class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center"
                >
                  <svg
                    class="w-4 h-4 text-blue-600 dark:text-blue-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </div>
              </div>
              <div>
                <h3
                  class="font-semibold text-blue-800 dark:text-blue-300 text-sm mb-1"
                >
                  {{ __("What is a 'device'?") }}
                </h3>
                <p class="text-blue-700 dark:text-blue-400 text-sm">
                  {{
                    __(
                      "Each device (phone, laptop, tablet) needs its own configuration to use the VPN. Create one for each device you want to protect."
                    )
                  }}
                </p>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
              <v-input
                :label="__('Device name')"
                v-model="form.name"
                required
                :error="errors.name"
                :placeholder="__('Example: My Phone, My Laptop, Home Tablet')"
                class="dark:bg-gray-800 dark:text-gray-200"
              />
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 ml-1">
                {{ __("Use a name that helps you identify this device") }}
              </p>
            </div>

            <div>
              <v-select
                :label="__('Select Server Location')"
                v-model="form.wireguard_id"
                :options="servers"
                required
                :error="errors.wireguard_id"
                label-key="country"
                value-key="id"
                class="dark:bg-gray-800 dark:text-gray-200"
              />
              <div class="flex items-start gap-2 mt-2 ml-1">
                <svg
                  class="w-4 h-4 text-green-500 dark:text-green-400 shrink-0 mt-0.5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  {{ __("Choose the server closest to you for better speed") }}
                </p>
              </div>
            </div>
          </div>

          <div
            class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-8"
          >
            <div class="flex items-start gap-3">
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
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"
                    />
                  </svg>
                </div>
              </div>
              <div>
                <h3
                  class="font-semibold text-yellow-800 dark:text-yellow-300 text-sm mb-2"
                >
                  {{ __("Important Information") }}
                </h3>
                <ul
                  class="space-y-2 text-yellow-700 dark:text-yellow-400 text-sm"
                >
                  <li class="flex items-start gap-2">
                    <span
                      class="w-5 h-5 bg-yellow-100 dark:bg-yellow-800 rounded-full text-yellow-600 dark:text-yellow-400 text-xs flex items-center justify-center"
                      >1</span
                    >
                    <span>{{
                      __("Save the QR code or file - you won't see it again")
                    }}</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <span
                      class="w-5 h-5 bg-yellow-100 dark:bg-yellow-800 rounded-full text-yellow-600 dark:text-yellow-400 text-xs flex items-center justify-center"
                      >2</span
                    >
                    <span>{{
                      __(
                        "You can use this configuration on only one device at a time"
                      )
                    }}</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <span
                      class="w-5 h-5 bg-yellow-100 dark:bg-yellow-800 rounded-full text-yellow-600 dark:text-yellow-400 text-xs flex items-center justify-center"
                      >3</span
                    >
                    <span>{{
                      __("Need help? Click the instructions button below")
                    }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-4">
            <button
              @click="showInstructions = true"
              class="flex-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 p-3 cursor-pointer rounded-md flex items-center justify-center gap-2 transition-colors"
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
                  d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              {{ __("Show Instructions First") }}
            </button>

            <button
              @click="create"
              :disabled="creating || !form.name || !form.wireguard_id"
              class="flex-1 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 p-3 text-white cursor-pointer rounded-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 transition-colors"
            >
              <svg
                v-if="creating"
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
                  d="M12 4v16m8-8H4"
                />
              </svg>
              {{ creating ? __("Creating...") : __("Create Device Now") }}
            </button>

            <button
              @click="dialog == false"
              class="flex-1 bg-red-100 hover:bg-red-200 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 p-3 cursor-pointer rounded-md transition-colors"
            >
              {{ __("Cancel") }}
            </button>
          </div>
        </div>

        <div v-else class="space-y-6">
          <div
            class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4"
          >
            <div class="flex items-center gap-3">
              <div class="shrink-0">
                <div
                  class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center"
                >
                  <svg
                    class="w-5 h-5 text-green-600 dark:text-green-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </div>
              </div>
              <div>
                <h3 class="font-bold text-green-800 dark:text-green-300">
                  {{ __("✅ Device Created Successfully!") }}
                </h3>
                <p class="text-green-700 dark:text-green-400 text-sm mt-1">
                  {{ __("Now save this configuration to use on your device:") }}
                </p>
              </div>
            </div>
          </div>

          <!-- QR Code -->
          <div class="text-center">
            <p class="mb-4 font-medium text-gray-700 dark:text-gray-300">
              {{ __("Scan this QR code with your WireGuard app") }}
            </p>
            <div class="flex justify-center">
              <canvas
                ref="qrCodeCanvas"
                class="p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg"
              ></canvas>
            </div>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
              {{ __("Or download the configuration file below") }}
            </p>
          </div>

          <div
            class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700"
          >
            <h3
              class="font-medium mb-3 dark:text-gray-200 flex items-center gap-2"
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
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              {{ __("Device Information") }}
            </h3>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="font-medium dark:text-gray-300">{{
                  __("Device Name")
                }}</span>
                <span class="dark:text-gray-200 font-semibold">{{
                  createdPeer.name
                }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="font-medium dark:text-gray-300">{{
                  __("VPN IP Address")
                }}</span>
                <span class="dark:text-gray-200 font-mono text-sm">{{
                  createdPeer.allowed_ips
                }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="font-medium dark:text-gray-300">{{
                  __("Server Location")
                }}</span>
                <span class="dark:text-gray-200">{{
                  createdPeer.wireguard?.server_name || "N/A"
                }}</span>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <button
              @click="downloadConfig"
              class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 p-4 text-white cursor-pointer rounded-lg flex flex-col items-center justify-center gap-2 transition-colors group"
            >
              <svg
                class="w-6 h-6 group-hover:scale-110 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
              <span class="font-medium">{{ __("Download Config") }}</span>
              <span class="text-xs opacity-80">{{ __(".conf file") }}</span>
            </button>

            <button
              @click="copyToClipboard"
              class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 p-4 text-white cursor-pointer rounded-lg flex flex-col items-center justify-center gap-2 transition-colors group"
            >
              <svg
                class="w-6 h-6 group-hover:scale-110 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                />
              </svg>
              <span class="font-medium">{{ __("Copy Config") }}</span>
              <span class="text-xs opacity-80">{{ __("Manual setup") }}</span>
            </button>

            <button
              @click="resetModal"
              class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 p-4 text-white cursor-pointer rounded-lg flex flex-col items-center justify-center gap-2 transition-colors group"
            >
              <svg
                class="w-6 h-6 group-hover:scale-110 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 13l4 4L19 7"
                />
              </svg>
              <span class="font-medium">{{ __("All Done") }}</span>
              <span class="text-xs opacity-80">{{ __("Close window") }}</span>
            </button>
          </div>

          <div
            class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-xl p-4"
          >
            <h4
              class="font-semibold text-purple-800 dark:text-purple-300 mb-3 flex items-center gap-2"
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
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              {{ __("How to Set Up Your Device") }}
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-3">
                <h5
                  class="font-medium text-purple-700 dark:text-purple-400 text-sm"
                >
                  {{ __("For Smartphones:") }}
                </h5>
                <ol
                  class="space-y-2 text-sm text-purple-600 dark:text-purple-300"
                >
                  <li class="flex items-start gap-2">
                    <span
                      class="w-5 h-5 bg-purple-100 dark:bg-purple-800 rounded-full text-purple-600 dark:text-purple-300 text-xs flex items-center justify-center mt-0.5"
                      >1</span
                    >
                    <span>{{
                      __("Download WireGuard app from App Store/Play Store")
                    }}</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <span
                      class="w-5 h-5 bg-purple-100 dark:bg-purple-800 rounded-full text-purple-600 dark:text-purple-300 text-xs flex items-center justify-center mt-0.5"
                      >2</span
                    >
                    <span>{{ __("Open app and tap '+' button") }}</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <span
                      class="w-5 h-5 bg-purple-100 dark:bg-purple-800 rounded-full text-purple-600 dark:text-purple-300 text-xs flex items-center justify-center mt-0.5"
                      >3</span
                    >
                    <span>{{
                      __("Select 'Scan from QR Code' and scan above")
                    }}</span>
                  </li>
                </ol>
              </div>
              <div class="space-y-3">
                <h5
                  class="font-medium text-purple-700 dark:text-purple-400 text-sm"
                >
                  {{ __("For Computers:") }}
                </h5>
                <ol
                  class="space-y-2 text-sm text-purple-600 dark:text-purple-300"
                >
                  <li class="flex items-start gap-2">
                    <span
                      class="w-5 h-5 bg-purple-100 dark:bg-purple-800 rounded-full text-purple-600 dark:text-purple-300 text-xs flex items-center justify-center mt-0.5"
                      >1</span
                    >
                    <span>{{
                      __("Download WireGuard app for Windows/Mac/Linux")
                    }}</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <span
                      class="w-5 h-5 bg-purple-100 dark:bg-purple-800 rounded-full text-purple-600 dark:text-purple-300 text-xs flex items-center justify-center mt-0.5"
                      >2</span
                    >
                    <span>{{
                      __("Click 'Add Tunnel' → 'Import tunnel from file'")
                    }}</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <span
                      class="w-5 h-5 bg-purple-100 dark:bg-purple-800 rounded-full text-purple-600 dark:text-purple-300 text-xs flex items-center justify-center mt-0.5"
                      >3</span
                    >
                    <span>{{ __("Select the downloaded .conf file") }}</span>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
      </template>
    </v-modal>

    <v-modal
      v-model="showInstructions"
      :title="__('Setup Instructions')"
      panel-class="w-full lg:w-3xl"
    >
      <template #body>
        <div class="space-y-6">
          <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <h3 class="font-bold text-blue-800 dark:text-blue-300 mb-2">
              {{ __("What You'll Need:") }}
            </h3>
            <ul class="space-y-2 text-blue-700 dark:text-blue-400">
              <li class="flex items-center gap-2">
                <svg
                  class="w-4 h-4 text-green-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                {{ __("WireGuard app installed on your device") }}
              </li>
              <li class="flex items-center gap-2">
                <svg
                  class="w-4 h-4 text-green-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                {{ __("A few minutes of your time") }}
              </li>
              <li class="flex items-center gap-2">
                <svg
                  class="w-4 h-4 text-green-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                {{
                  __(
                    "QR code scanner (for phones) or file browser (for computers)"
                  )
                }}
              </li>
            </ul>
          </div>

          <div class="space-y-4">
            <div
              v-for="(step, index) in setupSteps"
              :key="index"
              class="flex items-start gap-4"
            >
              <div class="shrink-0">
                <div
                  class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center font-bold text-gray-700 dark:text-gray-300"
                >
                  {{ index + 1 }}
                </div>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-1">
                  {{ step.title }}
                </h4>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                  {{ step.description }}
                </p>
              </div>
            </div>
          </div>

          <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
            <h4 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">
              {{ __("Important Tips:") }}
            </h4>
            <ul class="space-y-2 text-yellow-700 dark:text-yellow-400 text-sm">
              <li>
                {{ __("Save the QR code or file - you won't see it again") }}
              </li>
              <li>
                {{
                  __("Each configuration works on only one device at a time")
                }}
              </li>
              <li>{{ __("Choose a server close to you for better speed") }}</li>
              <li>{{ __("Need help? Contact support if you get stuck") }}</li>
            </ul>
          </div>

          <div class="flex justify-center pt-4">
            <button
              @click="showInstructions = false"
              class="px-6 py-2 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-md transition-colors"
            >
              {{ __("Got it, let's continue!") }}
            </button>
          </div>
        </div>
      </template>
    </v-modal>
  </div>
</template>

<script setup>
import VModal from "@vpn/components/VModal.vue";
import VInput from "@vpn/components/VInput.vue";
import VSelect from "@vpn/components/VSelect.vue";
import { ref, computed, nextTick, onUnmounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import QRious from "qrious";

const dialog = ref(false);
const showInstructions = ref(false);
const creating = ref(false);
const createdPeer = ref(null);
const qrCodeCanvas = ref(null);
let qriousInstance = null;

const emits = defineEmits(["created", "updated"]);

const page = usePage();
const form = ref({
  name: "",
  wireguard_id: null,
});
const errors = ref({});
const servers = ref([]);

const title = computed(() => {
  return createdPeer.value
    ? __("✅ Device Created Successfully!")
    : __("Create New VPN Device");
});

const setupSteps = [
  {
    title: __("Step 1: Choose a device name"),
    description: __(
      "Pick a name that helps you identify this device (e.g., 'My iPhone', 'Work Laptop')"
    ),
  },
  {
    title: __("Step 2: Select a server location"),
    description: __(
      "Choose the server closest to you for the fastest connection speed"
    ),
  },
  {
    title: __("Step 3: Save the configuration"),
    description: __(
      "Download the .conf file or scan the QR code immediately - you won't see it again!"
    ),
  },
  {
    title: __("Step 4: Set up WireGuard app"),
    description: __(
      "Install WireGuard on your device and import the configuration"
    ),
  },
  {
    title: __("Step 5: Connect and enjoy!"),
    description: __(
      "Turn on the VPN connection in WireGuard to protect your internet"
    ),
  },
];

const open = async () => {
  resetModal();
  dialog.value = !dialog.value;
  await listWireguardServer();
};

const resetModal = () => {
  form.value = { name: "", wireguard_id: null };
  createdPeer.value = null;
  errors.value = {};
  creating.value = false;

  if (qriousInstance) {
    qriousInstance = null;
  }
};

const create = async () => {
  errors.value = {};

  try {
    const res = await $server.post(page.props.routes.peers, form.value);
    if (res.status == 201) {
      createdPeer.value = res.data;
      emits("created");
      await nextTick();
      generateQRCode();
    }
  } catch (error) {
    if (error?.response?.data?.message) {
      $notify.error(__("Could not create device"), error.response.data.message);
    }

    if (error?.response?.status == 422) {
      errors.value = error.response.data.errors;
    }
  }
};

const generateQRCode = () => {
  if (!createdPeer.value?.config || !qrCodeCanvas.value) return;

  try {
    qriousInstance = new QRious({
      element: qrCodeCanvas.value,
      value: createdPeer.value.config,
      size: 256,
      level: "H",
      backgroundAlpha: 1.0,
      foregroundAlpha: 1.0,
    });
  } catch (error) {
    $notify.error(__("Failed to generate QR code"));
  }
};

const downloadConfig = () => {
  if (!createdPeer.value?.config) return;

  const configText = createdPeer.value.config;
  const fileName = `wireguard-${createdPeer.value.name
    .toLowerCase()
    .replace(/\s+/g, "-")}.conf`;

  const blob = new Blob([configText.trim()], {
    type: "application/octet-stream",
  });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");

  a.href = url;
  a.download = fileName;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);

  $notify.success(
    __("File downloaded!"),
    __("Now import this file into WireGuard app on your device.")
  );
};

const copyToClipboard = async () => {
  if (!createdPeer.value?.config) return;

  try {
    await navigator.clipboard.writeText(createdPeer.value.config);
    $notify.success(
      __("Copied to clipboard!"),
      __("Paste this into WireGuard's 'Add Tunnel' → 'Create from text'.")
    );
  } catch (error) {
    $notify.error(__("Failed to copy configuration"));
  }
};

const listWireguardServer = async () => {
  try {
    const res = await $server.get(page.props.routes.wireguard, {
      params: {
        per_page: 50,
      },
    });
    if (res.status == 200) {
      servers.value = res.data.data;
    }
  } catch (error) {
    console.error("Error loading servers:", error);
  }
};

onUnmounted(() => {
  qriousInstance = null;
});
</script>
