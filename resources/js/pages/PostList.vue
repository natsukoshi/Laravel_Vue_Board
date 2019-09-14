<template>
    <div>
        <p>
        <Postform v-on:reloadPosts="fetchPosts" />
        </p>
        <div class="postRaw" v-for="post in posts" :key="post.id">
             Name:{{ post.user.name }}<br>
             <router-link :to="`post/${ post.id }`">Title:{{ post.title}}</router-link> <br>

             {{ post.message }}
             {{ `post/${ post.id }` }}
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import Postform from '../components/Postform.vue'
import { mapGetters } from 'vuex'

export default {
    components: {
    Postform,

  },
  data () {
    return {
      posts: [],
      messasgeContent: '',
      user: '', //仮　削除予定
      num: 6,
    }
  },
  methods: {
    //投稿を全て取得する
    async fetchPosts () {
      const response = await axios.get('/api/posts');

    //   if (response.status !== OK) {
    //     this.$store.commit('error/setCode', response.status)
    //     return false
    //   }

      this.posts = response.data.data;
    },
    //フォームのメッセージを投稿する
    async postMessage () {
        const response = await axios.post('/api/posts',{
            message: this.messasgeContent
        })

        //to-do 投稿エラーだった場合の処理

        this.messasgeContent = '';
        this.fetchPosts();
    },
  },
  watch: {
    $route: {
      async handler () {
        await this.fetchPosts()
      },
      immediate: true
    }
  },
  computed: {
    //　store/authのステートを取得
    ...mapGetters('auth', [ //ストア内のパスを指定
        'isLoggedin'
    ])
  },
}
</script>
