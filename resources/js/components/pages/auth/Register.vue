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
        <h1 class="h3 mb-3 font-weight-normal">Cadastrar</h1>

        <label for="inputName" class="sr-only">Nome Completo</label>
        <input
          type="text"
          id="inputName"
          class="form-control"
          placeholder="Nome Completo"
          required
          v-model="form.name"
          autofocus
        />
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

        <label for="phone" class="sr-only">Whatsapp</label>
        <input
          type="tel"
          id="phone"
          pattern="[0-9]+"
          class="form-control"
          placeholder="Whatsapp"
          required
          v-model="form.whatsapp"
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

        <label for="reInputPassword" class="sr-only">Repita Senha</label>
        <input
          type="password"
          id="reInputPassword"
          class="form-control"
          placeholder="Repita Senha"
          required
          v-model="form.password_confirm"
        />

        <button class="btn btn-lg btn-primary btn-block mt-3" type="submit">Cadastrar</button>
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
        name: "",
        email: "",
        whatsapp: "",
        password: "",
        password_confirm: ""
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
        .post("/user/register", this.form)
        .then(response => {
          if (response.data.success) {
            this.$router.push({ path: "/register" });
            Swal.fire("Sucesso!", `${response.data.message}`, "success");
          } else if (response.data.success == 0 && response.data[0].email) {
            Swal.fire("Erro!", `${response.data[0].email}`, "error");
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