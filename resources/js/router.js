import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import PostList from './pages/PostList.vue'
import Login from './pages/Login.vue'

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
    component: Login
  }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history', //Historyモードにする。URLから#を除く
    routes
  })

  // VueRouterインスタンスをエクスポートする
  // app.jsでインポートするため
  export default router
