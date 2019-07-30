import Vue from 'vue';
import iView from 'iview';
import {router} from './router/index';
import {appRouter} from './router/router';
import store from './store';
import App from './app.vue';
import '@/locale';
import 'iview/dist/styles/iview.css';
import './styles/fonticon/iconfont.css';//引用字体
import VueI18n from 'vue-i18n';
import util from './libs/util';
import VueResource from 'vue-resource';
import md5 from 'js-md5';
import api_param from './api_param.js';
import iviewArea from 'iview-area';


global.api_param = api_param;

Vue.use(iviewArea);
Vue.use(VueI18n);
Vue.use(iView);
Vue.prototype.$md5 = md5;
Vue.use(VueResource);

Vue.filter('keepTwoNum', function (value) {
    value = Number(value);
    return value.toFixed(2);
})


Vue.prototype.httpPost = function (url, reqData, func) {
    this.$http.post(api_param.apiurl + url,
        reqData,
        {emulateJSON: true, headers: {'X-Access-Token': api_param.XAccessToken}}
    ).then(function (response) {
        //console.log(response);
        // 这里是处理正确的回调
        if (response.data.code === 200) {
            // this.$refs['ref'].resetFields();
            this.$Message.success(response.data.message);
            this.func();
        } else if (response.data.code === 401) {
            this.$store.commit('logout', this);
            this.$store.commit('clearOpenedSubmenu');
            this.$router.push({
                name: 'login'
            });
        } else {
            this.$Message.warning(response.data.message);
        }
    }, function (response) {
        // 这里是处理错误的回调
        this.$Message.warning('更新失败');
    });
};
new Vue({
    el: '#app',
    router: router,
    store: store,
    render: h => h(App),
    data: {
        currentPageName: ''
    },
    mounted() {
        this.currentPageName = this.$route.name;
        // 显示打开的页面的列表
        this.$store.commit('setOpenedList');
        this.$store.commit('initCachepage');
        // 权限菜单过滤相关
        let userTokens = window.localStorage.getItem('userTokens');
        if (userTokens == '') {
            this.$router.push({
                name: 'login'
            });
        }
        let menu = JSON.parse(window.localStorage.getItem('sideMenuList'));
        if (menu) {
            this.$store.commit('updateMenulist', menu);
        } else {
            this.$router.push({
                name: 'login'
            });
        }
        // iview-admin检查更新
        //util.checkUpdate(this);
    },
    created() {
        let tagsList = [];
        appRouter.map((item) => {
            if (item.children.length <= 1) {
                tagsList.push(item.children[0]);
            } else {
                tagsList.push(...item.children);
            }
        });
        this.$store.commit('setTagsList', tagsList);
    }
});
