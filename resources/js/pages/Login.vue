<template>
  <div>
    <h1>Login</h1>
    <div v-if="loginErrors" class="error">
      <ul v-if="loginErrors.email">
        <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="loginErrors.password">
        <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <form @submit.prevent="login">
      <label for="login-email">Email</label>
      <input type="text" id="login-email" v-model="loginForm.email" />
      <br>
      <label for="login-password">Password</label>
      <input type="password" id="login-password" v-model="loginForm.password" />
      <br>
      <button type="submit">Login</button>
    </form>
    <p>Password:{{loginForm.password}}</p>

    <h1>Register</h1>
    <div v-if="registerErrors" class="error">
      <ul v-if="registerErrors.email">
        <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="registerErrors.password">
        <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <form @submit.prevent="register">
      <p>
        <label for="register-name">Name</label>
        <input type="text" id="register-name" v-model="registerForm.name" />
      </p>
      <p>
        <label for="register-email">Email</label>
        <input type="text" id="register-email" v-model="registerForm.email" />
      </p>
      <p>
        <label for="register-password">Password</label>
        <input type="password" id="register-password" v-model="registerForm.password" />
      </p>
      <p>
        <label for="register-password">Password Confirm</label>
        <input
          type="password"
          id="register-password_confirmation"
          v-model="registerForm.password_confirmation"
        />
      </p>
      <button type="submit">Register</button>
    </form>
    <p>Password:{{registerForm.password}}</p>
  </div>
</template>

<script>
import axios from "axios";
import { mapState } from "vuex";

export default {
  data() {
    return {
      loginForm: {
        email: "",
        password: ""
      },
      registerForm: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "" // フィールド名＋_confirmationで同じ値かチェックする
      }
    };
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
      loginErrors: state => state.auth.loginErrorMessages,
      registerErrors: state => state.auth.registerErrorMessages
    })
  },
  methods: {
    async register() {
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch("auth/register", this.registerForm);

      if (this.apiStatus) {
        this.$router.push("/");
      }
    },

    async login() {
      console.log(this.loginForm);

      // authストアのloginアクションを呼び出す
      await this.$store.dispatch("auth/login", this.loginForm);

      if (this.apiStatus) {
        // トップページに移動する <router-link :to="/">と等価
        this.$router.push("/");
      }
    },

    async logout() {
      console.log("logoutメソッド");
      // authストアのloginアクションを呼び出す。
      //　auth.jsのapiStatusが更新されると、computedのapiStatusも更新される
      await this.$store.dispatch("auth/logout");

      if (this.apiStatus) {
        // トップページに移動する <router-link :to="/">と等価
        this.$router.push("/");
      }
    },

    clearError() {
      this.$store.commit("auth/setLoginErrorMessages", null);
      this.$store.commit("auth/setRegisterErrorMessages", null);

    }
  },
  created() {
    this.clearError();
  }
};
</script>
