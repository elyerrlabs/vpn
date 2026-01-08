<template>
  <div>
    <button
      @click="toggle"
      class="px-4 py-2 cursor-pointer bg-blue-500 text-white"
    >
      {{ buttonName }}
    </button>
    <v-modal v-model="dialog" :title="__(title)" panel-class="w-full lg:w-4xl">
      <template #body>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
          <v-input
            :label="__('Server name')"
            v-model="form.name"
            required
            :error="errors.name"
          />

          <v-input
            :label="__('Server IP')"
            v-model="form.ip"
            required
            :error="errors.ip"
          />

          <v-input
            :label="__('Server Port')"
            v-model="form.port"
            type="number"
            required
            :error="errors.port"
          />

          <v-input
            :label="__('Server Proxy Port')"
            v-model="form.proxy_port"
            type="number"
            required
            :error="errors.proxy_port"
          />

          <v-input
            :label="__('Server Socks Port')"
            v-model="form.socks_port"
            type="number"
            required
            :error="errors.socks_port"
          />

          <v-switch
            :label="__('Make hidden')"
            v-model="form.hidden"
            :error="errors.hidden"
          />
        </div>
        <div class="flex justify-between items-center">
          <button
            @click="execute"
            class="bg-blue-500 p-4 text-white cursor-pointer"
          >
            {{ __("Add Server") }}
          </button>

          <button
            @click="toggle"
            class="bg-red-500 p-4 text-white cursor-pointer"
          >
            {{ __("Cancel") }}
          </button>
        </div>
      </template>
    </v-modal>
  </div>
</template>
<script setup>
import VModal from "@vpn/components/VModal.vue";
import VInput from "@vpn/components/VInput.vue";
import VSwitch from "@vpn/components/VSwitch.vue";
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
const dialog = ref(false);

const emits = defineEmits(["created", "updated"]);

const props = defineProps({
  item: {
    type: Object,
    required: false,
    default: () => {},
  },
  title: {
    type: String,
    required: true,
    default: "Add new server",
  },
  buttonName: {
    type: String,
    required: true,
    default: "Add new server",
  },
});

const page = usePage();
const form = ref({});
const errors = ref({});

const toggle = () => {
  form.value.name = "";
  form.value.ip = "";
  form.value.port = "";
  form.value.proxy_port = "";
  form.value.socks_port = "";
  dialog.value = !dialog.value;
  errors.value = {};

  if (props.item?.id) {
    form.value = { ...props.item };
  }
};

const execute = async () => {
  if (props.item?.id) {
    await updateServer();
  } else {
    await addServer();
  }
};

const addServer = async () => {
  try {
    const res = await $server.post(page.props.servers.store, form.value);
    if (res.status == 201) {
      toggle();
      emits("created");
    }
  } catch (error) {
    if (error?.response?.data?.message) {
      $notify.error(error.response.data.message);
    }

    if (error?.response?.status == 422) {
      errors.value = error.response.data.errors;
    }
  }
};

const updateServer = async () => {
  try {
    const res = await $server.put(props.item.links.update, form.value);
    if (res.status == 200) {
      toggle();
      emits("updated");
      $notify.success(__("Server updated"));
    }
  } catch (error) {
    if (error?.response?.data?.message) {
      $notify.error(error.response.data.message);
    }

    if (error?.response?.status == 422) {
      errors.value = error.response.data.errors;
    }
  }
};
</script>
