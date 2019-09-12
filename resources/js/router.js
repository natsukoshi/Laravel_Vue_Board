import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import PostList from './pages/PostList.vue'
import Login from './pages/Login.vue'
import SystemError from './pages/Error.vue'
import PostDetaile from './pages/PostDetaile.vue'

// ストア
import store from './store'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    component: PostList
  },
  {
    path: '/login',
    component: Login,
    // ログイン済みでloginページにアクセスした場合、indexへリダイレクト(ページコンポーネントを切り替える)
    beforeEnter (to, from, next) { // ページコンポーネントが切り替わる直前に呼び出される関数
        if (store.getters['auth/isLoggedin']) {
          next('/')
        } else {
          next()
        }
      }
  },
  {//システムエラーページ
    path: '/500',
    component: SystemError
  },
   {//投稿の詳細ページ
    path: '/post/:id',
    name: 'post',
    component: PostDetaile,
  },

]

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history', //Historyモードにする。URLから#を除く
    routes
  })

  // VueRouterインスタンスをエクスポートする
  // app.jsでインポートするため
  export default router
