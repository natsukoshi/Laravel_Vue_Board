<template>
<div>
  <h1>Login</h1>
  <form @submit.prevent="login">
    <label for="login-email">Email</label>
    <input type="text" id="login-email" v-model="loginForm.email">
    <label for="login-password">Password</label>
    <input type="password" id="login-password" v-model="loginForm.password">
    <button type="submit">Login</button>
  </form>
    <p>Email:{{loginForm.email}}</p>
    <p>Password:{{loginForm.password}}</p>

  <h1>Register</h1>
  <form @submit.prevent="register">
  <p>
  <label for="register-name">Name</label>
<input type="text" id="register-name" v-model="registerForm.name">
</p>
<p>
    <label for="register-email">Email</label>
    <input type="text" id="register-email" v-model="registerForm.email">
    </p>
    <p>
    <label for="register-password">Password</label>
    <input type="password" id="register-password" v-model="registerForm.password">
    </p>
    <p>
    <label for="register-password">Password Confirm</label>
    <input type="password" id="register-password_confirmation" v-model="registerForm.password_confirmation">
    </p>
    <button type="submit">Register</button>
  </form>
    <p>Email:{{registerForm.email}}</p>
    <p>Password:{{registerForm.password}}</p>
</div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      loginForm: {
        email: '',
        password: ''
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '', // フィールド名＋_confirmationで同じ値かチェックする
      }
    }
  },
  methods: {
    async register() {
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch("auth/register", this.registerForm);

      // トップページに移動する
      this.$router.push("/");
    },
    async login() {
        console.log(this.loginForm)
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch("auth/login", this.loginForm);

      // トップページに移動する
      this.$router.push("/");
    },
    async logout() {
        console.log('logoutメソッド')
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch("auth/logout");

      // トップページに移動する
      this.$router.push("/");
    },
  }
};
</script>
