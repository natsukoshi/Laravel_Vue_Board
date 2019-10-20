<template >
  <div>
    <h2>
      <slot name="formTitle"></slot>
    </h2>
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
      <ul v-if="postErrors.parentID">
        <li v-for="msg in postErrors.parentID" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <form @submit.prevent="postMessage" v-if="isLoggedin" class="post_form">
      <div class="form_box">
        <label for="post-title">タイトル</label>
        <input type="text" id="post-title" v-model="titleContent" />
      </div>
      <div class="form_box">
        <label for="post-message">メッセージ</label>
        <textarea id="post-message" v-model="messasgeContent"></textarea>
      </div>
      <div class="form_box">
        <label for="imgSelectForm">画像</label>
        <input
          type="file"
          accept=".jpg, .jpeg, .gif, .png"
          name="img"
          @change="selectedFile"
          id="imgSelectForm"
        />
      </div>
      <button>メッセージ投稿</button>
    </form>
  </div>
</template>
<script>
import axios from "axios";
import { mapGetters, mapState } from "vuex";
import {
  OK,
  UNPROCESSABLE_ENTITY,
  INTERNAL_SERVER_ERROR,
  CSRF_TOKEN_ERROR
} from "../util";
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

      // パラメータの有無によって切り分け
      if (typeof this.$route.params.id === "undefined") {
        response = await axios
          .post("/api/posts", formData, config)
          .catch(err => err.response || err);
      } else {
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
      if (response.status === CSRF_TOKEN_ERROR) {
        console.log("CSRFTOKEN ERROR");

        this.postErrors = response.data.errors;
        console.log(this.postErrors);
        console.log(response);
        this.$router.push("/500");
        return;
      }
      if (response.status === INTERNAL_SERVER_ERROR) {
        this.postErrors = response.data.errors;
        console.log(this.postErrors);
        this.$router.push("/500");
        return;
      }

      this.titleContent = "";
      this.messasgeContent = "";
      this.postErrors = "";
      if (this.uploadFile != "") {
        this.uploadFile = "";
        document.getElementById("imgSelectForm").value = "";
      }

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
