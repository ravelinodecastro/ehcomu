<template>
  <div>
    <Header />
  
  </div>
</template>

<script>

import Header from "../Header.vue";


export default {
  components: { Header },

  mounted() {
    this.$store.dispatch("fetchUser");
  },
  methods: {},
  
};
</script>
