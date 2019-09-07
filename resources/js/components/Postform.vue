<template>
    <div>
        <form @submit.prevent="postMessage" v-if="isLoggedin">
            <textarea v-model="messasgeContent"></textarea>
            <button>メッセージ送信</button>
        </form>
    </div>
</template>
<script>
import axios from 'axios';
import { mapGetters } from 'vuex'

export default {
  data () {
    return {
      messasgeContent: '',
    }
  },
  methods: {
    async postMessage () {
        const response = await axios.post('/api/posts',{
            message: this.messasgeContent
        })

        //to-do 投稿エラーだった場合の処理

        this.messasgeContent = '';
    },
  },
  computed: {
    ...mapGetters('auth', [
        'isLoggedin'
    ])
  },
}
</script>
