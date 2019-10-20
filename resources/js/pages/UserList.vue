<template>
  <div>
    <div v-if="errors" class="error">{{errors}}</div>
    <div v-else>
      <table>
        <tr>
          <th>ID</th>
          <th>名前</th>
          <th>登録日時</th>
          <th>ユーザ削除</th>
        </tr>
        <tr v-for="user in users" :key="user.id">
          <td>{{user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.created_at }}</td>
          <td>
            <button>未実装</button>
          </td>
        </tr>
      </table>

      <Pagination :currentPage="currentPage" :lastPage="lastPage" />
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Pagination from "../components/Pagination.vue";

import { mapGetters } from "vuex";
import { POST_PAGE, INTERNAL_SERVER_ERROR, NOT_FOUND } from "../util";

export default {
  components: {
    Pagination
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },
  data() {
    return {
      users: [],
      currentPage: 0,
      lastPage: 0,
      errors: ""
    };
  },
  methods: {
    //ユーザを全て取得する
    async fetchUsers() {
      const response = await axios
        .get(`/api/admin/users?page=${this.page}`)
        .catch(function(err) {
          return err.response || err;
        });

      //   debugger;
      // 取得エラー時の処理
      if (response.status == "403") {
        // this.$router.push("/404");
        console.log(response.data);
        console.log(response.status);
        this.errors = response.data.message;
        return;
      }
      console.log(response.data);
      console.log(response.status);

      this.users = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;

      // 投稿を削除する
      // async deletePost(targetID) {
      //   const response = await axios
      //     .delete(`/api/posts/${targetID}`)
      //     .catch(function(err) {
      //       return err.response || err;
      //     });

      //   // 取得エラー時の処理
      //   if (response.status === NOT_FOUND) {
      //     this.$router.push("/404");
      //   } else if (response.status === INTERNAL_SERVER_ERROR) {
      //     this.$router.push("/500");
      //   }

      //   this.closeModal();
      //   this.fetchPosts();
      // },

      // deleteConfirm(targetID) {
      //   this.showModal = true;
      //   this.deleteTargetID = targetID;
      // },
      // closeModal() {
      //   this.showModal = false;
      //   this.deleteTargetID = null;
      // }
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
  },
  watch: {
    errorCode: {
      //算出プロパティerrorCodeに変更があったときに
      handler(val) {
        //呼ばれるコールバック関数。valはerrorCode()によって新しく取得された値（変更後の値）
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
        }
      },
      immediate: true //インスタンス生成時にも実行する
    },
    $route: {
      async handler() {
        await this.fetchUsers();
        this.$store.commit("error/setStatusCode", null);
      },
      immediate: true
    }
  }
};
</script>
