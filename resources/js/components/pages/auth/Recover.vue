<template>
  <div class="container-fluid h-100">
    <div class="row justify-content-center align-items-center h-100">
      <form class="form-signin text-center" @submit.prevent="authenticate">
        <img
          class="mb-4"
          src="/img/client-icon.png"
          alt
          width="72"
          height="72"
        />
        <h1 class="h3 mb-3 font-weight-normal">Recuperar Conta</h1>

        <label for="inputEmail" class="sr-only">E-mail</label>
        <input
          type="email"
          id="inputEmail"
          class="form-control"
          placeholder="E-mail"
          required
          v-model="form.email"
          autofocus
        />

        <button class="btn btn-lg btn-primary btn-block mt-3" type="submit">Recuperar</button>
        <hr />
        <router-link to="/login" class="btn btn-lg btn-secondary btn-block">Login</router-link>
      </form>
    </div>
  </div>
</template>

<script>
import Swal from "sweetalert2";
export default {
  name: "login",
  created() {},
  data() {
    return {
      form: {
        email: "",
        password: ""
      }
    };
  },
  methods: {
    authenticate() {
      let loader = this.$loading.show({
        // Optional parameters
        container: this.fullPage ? null : this.$refs.formContainer,
        canCancel: false,
        loader: "dots",
        color: "#238238"
      });
      axios
        .post("/user/reset-password", this.form)
        .then(response => {
          if (response.data.success) {
            Swal.fire("Sucesso!", `${response.data.message}`, "success");
            this.form.email = "";
          } else {
            Swal.fire("Erro!", `${response.data.message}`, "error");
          }
        })
        .catch(error => {
          Swal.fire("Erro!", "Falha ao realizar a operação", "error");
        })
        .finally(() => {
          loader.hide();
        });
    }
  }
};
</script>