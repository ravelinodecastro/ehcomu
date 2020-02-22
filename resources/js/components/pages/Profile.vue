<template>
  <form @submit.prevent="update">
    <div class="form-group">
      <label for="email">Nome</label>
      <input type="text" class="form-control" id="name" :value="user.name" name="name" required />
    </div>

    <div class="form-group">
      <label for="email">E-mail</label>
      <input
        type="email"
        class="form-control"
        id="email"
        aria-describedby="emailHelp"
        :value="user.email"
        disabled
        required
        name="email"
      />
      <small id="emailHelp" class="form-text text-muted">Endereço de e-mail não pode ser alterado</small>
    </div>

    <div class="form-group">
      <label for="phone">Whatsapp</label>
      <input
        type="tel"
        class="form-control"
        id="phone"
        aria-describedby="emailHelp"
        :value="user.whatsapp"
        pattern="[0-9]+"
        name="phone"
        required
      />
    </div>

    <div class="form-group">
      <!-- Only MG -->
      <label for="citySelect">Cidade</label>
      <select class="form-control" id="citySelect" v-model="user.city_id">
        <option value selected>Selecione a sua cidade</option>
        <option v-for="(city, idx) in cities" :key="idx" v-bind:value="city.id">{{city.city}}</option>
      </select>

      <small id="citySelect" class="form-text text-muted">Apenas regiões de Minas Gerais</small>
    </div>

    <div class="form-group">
      <label for="avatar">Avatar</label>

      <div class="custom-file">
        <input
          type="file"
          class="custom-file-input"
          id="avatar"
          accept="image/x-png, image/gif, image/jpeg"
          @change="handleFileUpload"
        />
        <label class="custom-file-label" for="avatar">Escolha sua foto de perfil</label>
      </div>
      <small id="avatar" class="form-text text-muted">Recomendado imagen 1:1 com máxima 128px</small>
    </div>

    <button type="submit" class="btn btn-secondary">Atualizar</button>
  </form>
</template>

<script>
import bsCustomFileInput from "bs-custom-file-input";
import Swal from "sweetalert2";

export default {
  name: "UpdateUser",

  mounted() {
    this.getCitiesByState();
    this.$store.dispatch("fetchUser");
    bsCustomFileInput.init();
    this.user = this.currentUser;
  },
  data() {
    return {
      user: {},
      cities: []
    };
  },
  methods: {
    handleFileUpload(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (files) {
        this.createImage(files[0]);
      }
    },
    createImage(file) {
      var image = new Image();
      var reader = new FileReader();
      reader.onload = e => {
        this.user = Object.assign(this.user, {
          imageToUpload: e.target.result
        });
      };

      reader.readAsDataURL(file);
    },
    update() {
      let loader = this.$loading.show({
        // Optional parameters
        container: this.fullPage ? null : this.$refs.contacts,
        canCancel: false,
        loader: "dots",
        color: "#238238"
      });
      axios
        .put(`/user/update`, this.user)
        .then(response => {
          if (response.data.success) {
            Swal.fire("Sucesso!", response.data.message, "success");
          } else {
            Swal.fire("Erro!", "Falha ao realizar a operação", "error");
          }
        })
        .catch(err => {
          Swal.fire("Erro!", "Falha ao realizar a operação", "error");
        })
        .finally(() => {
          loader.hide();
          this.$store.dispatch("fetchUser");
        });
    },
    async getCitiesByState(id = 1) {
      axios
        .get(`/state/${id}`)
        .then(response => {
          if (response.data.success) {
            this.cities = response.data.cities;
          } else {
            Swal.fire("Erro!", "Falha ao realizar a operação", "error");
          }
        })
        .catch(err => {
          Swal.fire("Erro!", "Falha ao realizar a operação", "error");
        })
        .finally(() => {});
    }
  },
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    }
  }
};
</script>