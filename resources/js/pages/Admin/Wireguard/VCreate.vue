<template>
  <div>
    <v-button
      @click="toggle"
      :label="item?.id ? '' : __('Create new server')"
      :title="item?.id ? __('Update server') : __('Create new server')"
      :round="item?.id ? true : false"
      :variant="item?.id ? 'success' : 'secondary'"
      :icon="item?.id ? 'mdi mdi-pencil' : 'mdi mdi-plus'"
    />

    <v-modal
      v-model="dialog"
      :title="item?.id ? __('Update server') : __('Create server')"
      panel-class="w-full lg:w-5xl"
    >
      <template #body>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
          <v-input
            :label="__('Server name')"
            v-model="form.name"
            required
            :error="form.errors.name"
          />

          <v-input
            :label="__('Listen port')"
            v-model="form.listen_port"
            required
            :error="form.errors.listen_port"
          />

          <v-select
            :label="__('Server list')"
            v-model="form.server_id"
            :options="servers"
            required
            :error="form.errors.server_id"
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
          <!--
          <v-select
          :label="__('Interface list')"
          v-model="form.network_interface"
          :options="interfaces"
          required
          :error="form.errors.network_interface"
          label-key="name"
          value-key="id"
          >
        </v-select>
        

          <v-input
            :label="__('DNS Server')"
            v-model="form.dns"
            required
            :error="form.errors.dns"
          />

          <v-switch
            :label="__('DNS Server enabled')"
            v-model="form.dns_enabled"
            :error="form.errors.dns_enabled"
          />-->

          <v-switch
            :label="__('Mounted')"
            v-model="form.mounted"
            :error="form.errors.mounted"
          />
          <v-switch
            :label="__('Public server')"
            v-model="form.public"
            :error="form.errors.public"
          />

          <v-switch
            v-if="item?.id"
            :label="__('Restart server')"
            v-model="form.restart"
            :error="form.errors.restart"
          />
        </div>
        <div class="flex justify-end items-center">
          <v-button
            @click="execute"
            :label="item?.id ? __('Update Server') : __('Add Server')"
            :title="item?.id ? __('Update Server') : __('Add Server')"
            variant="success"
            :round="item?.id ? true : false"
          />
        </div>
      </template>
    </v-modal>
  </div>
</template>
<script setup>
import VModal from "@vpn/components/VModal.vue";
import VInput from "@vpn/components/VInput.vue";
import VButton from "@vpn/components/VButton.vue";
import VSwitch from "@vpn/components/VSwitch.vue";
import VSelect from "@vpn/components/VSelect.vue";
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
const dialog = ref(false);

const emits = defineEmits(["created", "updated"]);

const props = defineProps({
  item: {
    type: Object,
    default: () => {},
  },
});

const page = usePage();
const form = useForm({
  name: null,
  listen_port: null,
  dns: null,
  dns_enabled: null,
  network_interface: "eth0",
  mounted: true,
  public: null,
  server_id: null,
});

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
  internal: true,
});

const toggle = async () => {
  dialog.value = !dialog.value;
  form.resetAndClearErrors();

  if (props.item?.id) {
    form.name = props.item?.name;
    form.listen_port = props.item?.listen_port;
    form.dns = props.item?.dns;
    form.dns_enabled = props.item?.dns_enabled;
    form.network_interface = props.item?.network_interface;
    form.mounted = props.item?.mounted;
    form.public = props.item?.public;
    form.server_id = props.item.server.id;
  }

  await getServers();
};

const execute = () => {
  if (props.item?.id) {
    updateServer();
  } else {
    addServer();
  }
};

const addServer = async () => {
  form.post(page.props.routes.wireguard, {
    preserveScroll: true,
    preserveState: true,
    forceFormData: true,
    onSuccess: (res) => {
      emits("created");
      $notify.success(__("New wireguard server created successfully"));
      form.resetAndClearErrors();
    },
    onError: (e) => {
      console.log(e);
    },
  });
};

const updateServer = () => {
  form.put(props.item.links.update, {
    preserveScroll: true,
    preserveState: true,
    forceFormData: true,
    onSuccess: (res) => {
      emits("created");
      $notify.success(__("wireguard server updated successfully"));
    },
    onError: (e) => {
      console.log(e);
    },
    onFinish: () => {
      dialog.value = false;
    },
  });
};

const searchServer = async (value) => {
  search_server.value.slug = value;

  await getServers();
};

const getServers = async () => {
  try {
    const res = await $server.get(page.props.api.servers, {
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
