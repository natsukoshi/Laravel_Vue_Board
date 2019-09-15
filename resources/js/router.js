import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import PostList from './pages/PostList.vue'
import Login from './pages/Login.vue'
import SystemError from './pages/Error.vue'
import Notfound from './pages/Notfound.vue'
import PostDetaile from './pages/PostDetaile.vue'

// ストア
import store from './store'

//定数
import { POST_PAGE, REPLY_PAGE } from './util'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
    {
        path: '/',
        component: PostList,
        beforeEnter(to, from, next) { // ページコンポーネントが切り替わる直前に呼び出される関数
            store.dispatch("auth/setPage", POST_PAGE)
            next()
        }
    },
    {
        path: '/login',
        component: Login,
        // ログイン済みでloginページにアクセスした場合、indexへリダイレクト(ページコンポーネントを切り替える)
        beforeEnter(to, from, next) { // ページコンポーネントが切り替わる直前に呼び出される関数
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
    {//見つかりませんでしたエラーページ
        path: '/404',
        component: Notfound
    },
    {//投稿の詳細ページ
        path: '/post/:id',
        name: 'post',
        component: PostDetaile,
        beforeEnter(to, from, next) { // ページコンポーネントが切り替わる直前に呼び出される関数
            store.dispatch("auth/setPage", REPLY_PAGE)
            // store.dispatch("auth/setParentPostID", id)
            next()
        }
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
