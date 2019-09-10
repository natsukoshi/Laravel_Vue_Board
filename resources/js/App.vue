<template>
    <div>
    <header>
      <Header />
    </header>
        <main>
            <div class="container">
                  <RouterView />
            </div>
        </main>
    </div>
</template>
<script>

import Header from './components/Header.vue'
import { INTERNAL_SERVER_ERROR } from './util'

export default {
  components: {
    Header,

  },
   computed: {
    errorCode () {
      return this.$store.state.error.statusCode
    }
  },
  watch: {
    errorCode: { //算出プロパティerrorCodeに変更があったときに
      handler (val) {   //呼ばれるコールバック関数。valはerrorCode()によって新しく取得された値（変更後の値）
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        }
      },
      immediate: true  //インスタンス生成時にも実行する
    },
    $route () {
      this.$store.commit('error/setStatusCode', null)
    }
  }

}
</script>
