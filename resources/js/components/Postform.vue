<template>
  <div class="post_form">
    <div v-if="postErrors" class="error">
      <ul v-if="postErrors.title">
        <li v-for="msg in postErrors.title" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="postErrors.message">
        <li v-for="msg in postErrors.message" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <form @submit.prevent="postMessage" v-if="isLoggedin">
      タイトル：
      <input type="text" v-model="titleContent" />
      <br />メッセージ：
      <textarea v-model="messasgeContent"></textarea>
      <br />
      <button>メッセージ送信</button>
    </form>
  </div>
</template>
<script>
import axios from "axios";
import { mapGetters, mapState } from "vuex";
import { OK, UNPROCESSABLE_ENTITY } from "../util";
import { POST_PAGE, REPLY_PAGE } from "../util";

export default {
  data() {
    return {
      titleContent: "",
      messasgeContent: "",
      postErrors: ""
    };
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters("auth", ["isLoggedin", "whichPage", "parentPostID"])
  },
  methods: {
    async postMessage() {
      console.log(this.whichPage);
      let response;
      switch (this.whichPage) {
        case POST_PAGE:
          console.log("投稿");
          response = await axios
            .post("/api/posts", {
              title: this.titleContent,
              message: this.messasgeContent
            })
            .catch(err => err.response || err);
          break;

        case REPLY_PAGE:
          console.log("返信");
          response = await axios
            .post(`/api/posts/${this.parentPostID}`, {
              title: this.titleContent,
              message: this.messasgeContent,
              parentID: this.parentPostID
            })
            .catch(err => err.response || err);

          break;
        default:
        //todo エラー処理
      }

      console.log("エラー判定前");
      //バリデーションエラー
      if (response.status === UNPROCESSABLE_ENTITY) {
        console.log("バリデーションエラー");

        this.postErrors = response.data.errors;
        return;
      }

      console.log("エラー判定後");
      //to-do 投稿エラーだった場合の処理
      // エラーレスポンスを受け取ったときエラーページへ飛ばす

      this.titleContent = "";
      this.messasgeContent = "";

      console.log("emit前");
      this.$emit("reloadPosts");
    }
  }
};
</script>
