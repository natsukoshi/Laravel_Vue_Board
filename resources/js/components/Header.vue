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
  computed: {
    ...mapGetters("auth", ["username", "isLoggedin"]),
    isLoginPage() {
      return this.$route.path == "/login";
    }
  },
  methods: {
    async logout() {
      await this.$store.dispatch("auth/logout");
      this.$router.push("/login");
    }
  }
};
</script>
