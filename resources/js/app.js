/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// import './bootstrap'    //axios使用時にCSRF対策用のトークンを付与
import Vue from 'vue'

// ルーティングの定義をインポートする
import router from './router'
// ストアのインデックスをインポートする
import store from './store'
// ルートコンポーネントをインポートする
import App from './App.vue'


const createApp = async () => {
    // インスタンス生成前にログインチェックを行う
    await store.dispatch('auth/loggedinUser')

    new Vue({
        el: '#app',
        router, //ルーティング定義の読み込み
        store, //ストアの使用宣言
        components: { App }, //ルートコンポーネントの使用宣言
        template: '<App />'   //ルートコンポーネントの描画
    })
}

createApp()
