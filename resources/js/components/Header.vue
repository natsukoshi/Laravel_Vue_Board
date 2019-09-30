<template>
  <header class="header">
    <div v-if="isLoggedin">
      <div class="userName">User:{{ username }}</div>
      <button @click="logout" class="login-logout">Logout</button>
    </div>
    <div v-else-if="isLoginPage">
      <RouterLink to="/" class="login-logout">Top</RouterLink>
    </div>
    <div v-else>
      <RouterLink to="/login" class="login-logout">Login / Register</RouterLink>
    </div>
  </header>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  // data() {
  //     return {
  //         // isLoggedin: false,
  //         // userName:  'tanaka'
  //     }
  // },
  computed: {
    ...mapGetters("auth", ["username", "isLoggedin"]),
    isLoginPage() {
      return this.$route.path == "/login";
    }
    // username () {
    //     return this.$store.getters['auth/username']
    // },
    // isLoggedin() {
    //     return this.$sotre.getters['auth/isLoggedin']
    // }
  },
  methods: {
    async logout() {
      await this.$store.dispatch("auth/logout");
      this.$router.push("/login");
    }
  }
};
</script>
