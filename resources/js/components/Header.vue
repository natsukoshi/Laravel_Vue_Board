<template>
  <header class="header">
    <div v-if="isLoggedin">
        <button @click="logout">Logout</button>
    </div>
    <RouterLink to="/login">
      Login / Register
    </RouterLink>

    <p >User:{{ username }}</p>
  </header>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
    data() {
        return {
            // isLoggedin: false,
            // userName:  'tanaka'
        }
    },
    computed: {
        ...mapGetters('auth', [
            'username',
            'isLoggedin'
        ])
        // username () {
        //     return this.$store.getters['auth/username']
        // },
        // isLoggedin() {
        //     return this.$sotre.getters['auth/isLoggedin']
        // }
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push('/login')
    }
  }
}
</script>
