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
            v-model="form.slug"
            required
            :error="errors.slug"
          />

          <v-input
            :label="__('Listen port')"
            v-model="form.listen_port"
            required
            :error="errors.listen_port"
          />

          <v-select
            :label="__('Server list')"
            v-model="form.server_id"
            :options="servers"
            required
            :error="errors.server_id"
            label-key="name"
            value-key="id"
            :searchable="true"
            @search="searchServer"
          >
            <template #selected="{ option }">
              <span>
                {{
                  option ? option.name + " - " + option.ip : __("Select server")
                }}
              </span>
            </template>
            <template #option="{ option }">
              <span
                class="px-4 py-2 block uppercase font-semibold hover:bg-blue-500"
              >
                {{ option.name }} - {{ option.ip }}
              </span>
            </template>
          </v-select>

          <v-select
            :label="__('Interface list')"
            v-model="form.network_interface"
            :options="interfaces"
            required
            :error="errors.network_interface"
            label-key="name"
            value-key="id"
          >
          </v-select>

          <v-input
            :label="__('DNS Server')"
            v-model="form.dns"
            required
            :error="errors.dns"
          />

          <v-switch
            :label="__('DNS Server enabled')"
            v-model="form.dns_enabled"
            :error="errors.dns_enabled"
          />

          <v-switch
            :label="__('Mounted')"
            v-model="form.mounted"
            :error="errors.mounted"
          />
          <v-switch
            :label="__('Public server')"
            v-model="form.public"
            :error="errors.public"
          />
          <v-switch
            v-if="item?.id"
            :label="__('Restart server')"
            v-model="form.restart"
            :error="errors.restart"
          />
        </div>
        <div class="flex justify-between items-center">
          <button
            @click="execute"
            class="bg-blue-500 p-4 text-white cursor-pointer"
          >
            {{ item?.id ? __("Update Server") : __("Add Server") }}
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
import VSelect from "@vpn/components/VSelect.vue";
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
const servers = ref([]);
const interfaces = ref([
  {
    id: "eth0",
    name: "eth0",
  },
]);

const search_server = ref({
  slug: "",
  per_page: 50,
  hidden: false,
});

const toggle = async () => {
  form.value.slug = "";
  form.value.listen_port = "";
  form.value.dns = "";
  form.value.dns_enabled = false;
  form.value.network_interface = "";
  form.value.mounted = true;
  form.value.public = true;
  form.value.server_id = "";
  dialog.value = !dialog.value;
  errors.value = {};

  if (props.item?.id) {
    form.value = { ...props.item };
    form.value.server_id = props.item.server.id;
  }

  await getServers();
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
    const res = await $server.post(page.props.wireguard.store, form.value);
    if (res.status == 201) {
      toggle();
      emits("created");
      $notify.success(__("New wireguard server created successfully"));
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
      $notify.success(__("wireguard server updated successfully"));
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

const searchServer = async (value) => {
  search_server.value.slug = value;

  await getServers();
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
</script>
