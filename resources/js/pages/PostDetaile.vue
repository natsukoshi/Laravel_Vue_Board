<template>
  <div>
    <router-link to="/">Topへ戻る</router-link>

    <div v-if="posts" class="postRaw">
      Title:{{ posts.title }}
      <br />
      Name:{{ posts.user.name }}
      <br />
      <div class="img" v-if="posts.image">
        <img v-bind:src="posts.image.file_url" alt="投稿画像" />
      </div>
      Message:{{ posts.message }}
      <br />
    </div>

    <div v-if="posts && posts.reply">
      <h2>返信</h2>
      <div class="postRaw" v-for="reply in posts.reply" :key="reply.id">
        Title:{{ reply.title }}
        <br />
        Name:{{ reply.user.name }}
        <br />
        <div class="img" v-if="reply.image">
          <img v-bind:src="reply.image.file_url" alt="投稿画像" />
        </div>
        Message:{{ reply.message }}
        <br />

        <button
          class="delete_button"
          v-if="reply.user.id == userID"
          @click="deleteReply(reply.id)"
        >削除</button>
        {{reply.user.id}}:{{userID}}
      </div>
    </div>

    <h2>返信フォーム</h2>
    <Postform v-on:reloadPosts="fetchPost" />
  </div>
</template>

<script>
import axios from "axios";
import Postform from "../components/Postform.vue";
import { mapGetters } from "vuex";
import { NOT_FOUND, INTERNAL_SERVER_ERROR } from "../util";

export default {
  components: {
    Postform
  },

  data() {
    return {
      posts: null,
      messasgeContent: ""
    };
  },

  methods: {
    //投稿と返信を取得する
    async fetchPost() {
      const response = await axios
        .get(`/api/posts/${this.$route.params.id}`)
        .catch(function(err) {
          return err.response || err;
        });

      // 取得エラー時の処理
      if (response.status === NOT_FOUND) {
        this.$router.push("/404");
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        this.$router.push("/500");
      }

      this.posts = response.data;
      console.log(this.posts.reply);
    },

    //返信を削除する
    async deleteReply(targetID) {
      const response = await axios
        .delete(`/api/reply/${targetID}`)
        .catch(function(err) {
          return err.response || err;
        });

      // 取得エラー時の処理
      if (response.status === NOT_FOUND) {
        this.$router.push("/404");
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        this.$router.push("/500");
      }

      this.fetchPost();

      console.log("削除完了");
    }
  },

  watch: {
    $route: {
      async handler() {
        await this.$store.dispatch(
          "auth/setParentPostID",
          this.$route.params.id
        );
        await this.fetchPost();
        console.log("ストアに値をセットした" + this.$route.params.id);
      },
      immediate: true
    }
  },
  computed: {
    //　store/authのステートを取得
    ...mapGetters("auth", [
      //ストア内のパスを指定
      "isLoggedin"
    ]),
    userID() {
      return this.$store.getters["auth/userID"];
    }
  }
};
</script>
