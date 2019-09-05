<template>
  <header class="header">
    <div v-if="isLoggedin === true">{{isLoggedin}}
        <button @click="logout">Logout</button>
    </div>
    <RouterLink to="/login" v-else>
      Login / Register
    </RouterLink>
    <p>User:{{userName}}</p>
  </header>
</template>

<script>
export default {
    data() {
        return {
            isLoggedin: false,
            userName:  this.$store.user
        }
    },
    computed: {
    setUser () {
        if(this.$store.user != null){
            this.isLoggedin = true;
            this.userName = this.$store.user.name;
        }
    }
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push('/login')
    }
  }
}
</script>
