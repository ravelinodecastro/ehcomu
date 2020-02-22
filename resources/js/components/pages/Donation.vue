<template>
  <div>
    <Header />

    <div class="d-flex justify-content-center align-items-center" style="height: 80vh; width: 100%">
      <div style="width: 22em;" class="text-white">
        <div class="alert alert-success" role="alert">Processando...</div>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import Header from "../Header.vue";
export default {
  components: { Header },
  mounted() {
    this.check();
  },

  data() {
    return {};
  },
  methods: {
    check() {
      let loader = this.$loading.show({
        // Optional parameters
        container: this.fullPage ? null : this.$refs.formContainer,
        canCancel: false,
        loader: "dots",
        color: "#238238"
      });
      axios
        .get("/user/mercadopago/?" + window.location.search.replace("?", ""))
        .then(response => {
          if (response.data.success) {
            Swal.fire("Sucesso!", response.data.message, "success");
            this.$router.push({ path: "/profile" });
          } else {
            this.$router.push({ path: "/" });
            Swal.fire("Erro!", response.data.message, "error");
          }
        })
        .catch(err => {
          this.$router.push({ path: "/" });
          Swal.fire("Erro!", "Falha ao realizar a operação", "error");
        })
        .finally(() => {
          loader.hide();
        });
    }
  },
  computed: {}
};
</script>

<style scoped>
</style>

