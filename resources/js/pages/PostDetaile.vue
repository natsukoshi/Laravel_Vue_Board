<template>
    <div>
    <p>
        <Postform />
        </p>
        詳細ページ
        {{ $route.params.id }}

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
    //投稿と返信を取得する
    async fetchPost (postID) {
        console.log(postID)
      const response = await axios.get(`/api/post/${postID}`);


    //   if (response.status !== OK) {
    //     this.$store.commit('error/setCode', response.status)
    //     return false
    //   }

      this.posts = response.data.data;
    },
    //フォームのメッセージを返信として投稿する
    async replyMessage () {
        const response = await axios.post('/api/post',{
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
        await this.fetchPost($route.params.id)
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
