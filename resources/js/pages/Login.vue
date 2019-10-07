<template>
  <div>
    <h1>ログイン</h1>
    <div v-if="loginErrors" class="error">
      <ul v-if="loginErrors.email">
        <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="loginErrors.password">
        <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <form @submit.prevent="login" class="post_form">
      <div class="form_box">
        <label for="login-email" class="left_label">メールアドレス</label>
        <input type="text" class="right_input" id="login-email" v-model="loginForm.email" />
      </div>
      <div class="form_box">
        <label for="login-password">パスワード</label>
        <input type="password" id="login-password" v-model="loginForm.password" />
      </div>
      <button type="submit">ログイン</button>
    </form>

    <h1>新規登録</h1>
    <div v-if="registerErrors" class="error">
      <ul v-if="registerErrors.email">
        <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="registerErrors.password">
        <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <form @submit.prevent="register" class="post_form">
      <div class="form_box">
        <label for="register-name">名前</label>
        <input type="text" id="register-name" v-model="registerForm.name" />
      </div>
      <div class="form_box">
        <label for="register-email">メールアドレス</label>
        <input type="text" id="register-email" v-model="registerForm.email" />
      </div>
      <div class="form_box">
        <label for="register-password">パスワード</label>
        <input type="password" id="register-password" v-model="registerForm.password" />
      </div>
      <div class="form_box">
        <label for="register-password">
          パスワード
          <br />(確認)
        </label>
        <input
          type="password"
          id="register-password_confirmation"
          v-model="registerForm.password_confirmation"
        />
      </div>
      <button type="submit">Register</button>
    </form>

    <h1>googleアカウントでログイン</h1>
    <div class="form_box">
      <a href="/auth/google">
        <img
          src="css/btn_google_signin_dark_normal_web.png"
          class="google_button"
          alt="googleアカウントでログイン"
        />
      </a>
    </div>
  </div>
</template>

<script>
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

//cookie値を連想配列として取得する
function getCookieArray() {
  var arr = new Array();
  if (document.cookie != "") {
    var tmp = document.cookie.split("; ");
    for (var i = 0; i < tmp.length; i++) {
      var data = tmp[i].split("=");
      arr[data[0]] = decodeURIComponent(data[1]);
    }
  }
  return arr["XSRF-TOKEN"];
}
console.log(getCookieArray());
</script>
