<template>
  <div>
    <v-button
      @click="toggle"
      :label="item?.id ? '' : __('Add new server')"
      :title="item?.id ? __('Update server') : __('Add new server')"
      :round="item?.id ? true : false"
      :icon="item?.id ? 'mdi mdi-pencil' : 'mdi mdi-plus'"
      :variant="item?.id ? 'success' : 'secondary'"
    />
    <v-modal
      v-model="dialog"
      :title="item?.id ? __('Edit server information') : 'Add new server'"
      panel-class="w-full lg:w-6xl"
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
            :label="__('Server IP')"
            v-model="form.ip"
            required
            :error="form.errors.ip"
          />

          <v-input
            :label="__('Server Port')"
            v-model="form.port"
            type="number"
            required
            :error="form.errors.port"
          />

          <v-input
            :label="__('Server Proxy Port')"
            v-model="form.proxy_port"
            type="number"
            required
            :error="form.errors.proxy_port"
          />

          <v-input
            :label="__('Server Socks Port')"
            v-model="form.socks_port"
            type="number"
            required
            :error="form.errors.socks_port"
          />

          <v-switch
            :label="__('Make hidden')"
            v-model="form.hidden"
            :error="form.errors.hidden"
          />
        </div>
        <div class="flex justify-end items-center">
          <v-button
            @click="execute"
            :label="item?.id ? __('Update server') : __('Add Server')"
          />
        </div>
      </template>
    </v-modal>
  </div>
</template>
<script setup>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VButton from "@/components/VButton.vue";
import VSwitch from "@/components/VSwitch.vue";
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
const dialog = ref(false);

const emits = defineEmits(["created", "updated"]);

const props = defineProps({
  item: {
    type: Object,
    required: false,
    default: () => {},
  },
});

const page = usePage();
const form = useForm({
  name: "",
  ip: "",
  port: 50050,
  proxy_port: "",
  socks_port: "",
  hidden: "",
});

const toggle = () => {
  form.resetAndClearErrors();
  dialog.value = !dialog.value;

  if (props.item?.id) {
    form.name = props.item.name;
    form.ip = props.item.ip;
    form.port = props.item.port;
    form.proxy_port = props.item.proxy_port;
    form.socks_port = props.item.socks_port;
    form.hidden = props.item.hidden;
  }
};

const execute = () => {
  if (props.item?.id) {
    updateServer();
  } else {
    addServer();
  }
};

const addServer = async () => {
  form.post(page.props.routes.servers, {
    preserveScroll: true,
    preserveState: true,
    forceFormData: true,
    onSuccess: (res) => {
      emits("created");
      form.resetAndClearErrors();
      $notify.success(__("Server created successfully"));
      dialog.value = false;
    },
    onError: (e) => {
      console.log(e);
    },
    onFinish: () => {},
  });
};

const updateServer = () => {
  form.put(props.item.links.update, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: (res) => {
      emits("updated");
      $notify.success(__("Server updated"));
      dialog.value = false;
    },
    onError: (e) => {
      console.log(e);
    },
    onFinish: () => {},
  });
};
</script>
