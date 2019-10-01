<template>
  <div class="post_form">
    <div v-if="postErrors" class="error">
      <ul v-if="postErrors.title">
        <li v-for="msg in postErrors.title" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="postErrors.message">
        <li v-for="msg in postErrors.message" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="postErrors.img">
        <li v-for="msg in postErrors.img" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <form @submit.prevent="postMessage" v-if="isLoggedin">
      タイトル：
      <input type="text" v-model="titleContent" />
      <br />メッセージ：
      <textarea v-model="messasgeContent"></textarea>
      <br />
      <input type="file" name="img" @change="selectedFile" id="imgSelectForm" />
      <button>メッセージ送信</button>
    </form>
  </div>
</template>
<script>
import axios from "axios";
import { mapGetters, mapState } from "vuex";
import { OK, UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR } from "../util";
import { POST_PAGE, REPLY_PAGE } from "../util";

export default {
  data() {
    return {
      titleContent: "",
      messasgeContent: "",
      postErrors: "",
      uploadFile: ""
    };
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters("auth", ["isLoggedin"])
  },
  methods: {
    async postMessage() {
      let response;
      const config = { headers: { "content-type": "multipart/form-data" } };

      const formData = new FormData();
      formData.append("title", this.titleContent);
      formData.append("message", this.messasgeContent);

      if (this.uploadFile !== "") {
        formData.append("img", this.uploadFile);
      }
      console.log(formData);
      console.log(this.$route.params.id);

      // パラメータの有無によって切り分け
      if (typeof this.$route.params.id === "undefined") {
        console.log("投稿");
        response = await axios
          .post("/api/posts", formData, config)
          .catch(err => err.response || err);
      } else {
        console.log("返信");
        formData.append("parentID", this.$route.params.id);
        response = await axios
          .post(`/api/reply/${this.$route.params.id}`, formData, config)
          .catch(err => err.response || err);
      }

      //バリデーションエラー
      if (response.status === UNPROCESSABLE_ENTITY) {
        console.log("バリデーションエラー");

        this.postErrors = response.data.errors;
        console.log(this.postErrors);
        return;
      }
      if(response.status === INTERNAL_SERVER_ERROR){
          console.log("500エラー");

        this.postErrors = response.data.errors;
        console.log(this.postErrors);
        return;
      }

      console.log("エラー判定後");
      //to-do 投稿エラーだった場合の処理
      // エラーレスポンスを受け取ったときエラーページへ飛ばす

      this.titleContent = "";
      this.messasgeContent = "";
      this.postErrors = "";
      if (this.uploadFile != "") {
        this.uploadFile = "";
        document.getElementById("imgSelectForm").value = "";
      }

      console.log("emit前");
      this.$emit("reloadPosts");
    },

    // 画像のアップロード
    selectedFile(e) {
      e.preventDefault();
      this.uploadFile = e.target.files[0];
      console.log(this.uploadFile);
    }
  }
};
</script>
