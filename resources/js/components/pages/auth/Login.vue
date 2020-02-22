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
        <h1 class="h3 mb-3 font-weight-normal">Área de Login</h1>

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

        <label for="inputPassword" class="sr-only">Senha</label>
        <input
          type="password"
          id="inputPassword"
          class="form-control"
          placeholder="Senha"
          required
          v-model="form.password"
        />

        <button class="btn btn-lg btn-primary btn-block mt-3" type="submit">Login</button>
        <router-link to="/reset-password" class="btn btn-link btn-block">Esquceu a Senha?</router-link>
        <hr />
        <router-link to="/register" class="btn btn-lg btn-secondary btn-block">Cadastrar</router-link>
      </form>
    </div>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import {setAuthorization} from "../../../helpers/general"
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
        .post("/user/login", this.form)
        .then(response => {
          if (response.data.success) {
            setAuthorization(response.data.token)
            this.$store.commit("loginSuccess", response.data);
            this.$router.push({ path: "/" });
            this.$notice["success"]({
              title: `Login`,
              description: `Seja bem-vindo  ${response.data.user.name}`
            });
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