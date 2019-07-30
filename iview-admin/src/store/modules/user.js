import Cookies from 'js-cookie';

const user = {
    state: {},
    mutations: {
        logout (state, vm) {
            Cookies.remove('user');
            Cookies.remove('uid');
            Cookies.remove('district');
            Cookies.remove('password');
            Cookies.remove('access');
            // 恢复默认样式
            let themeLink = document.querySelector('link[name="theme"]');
            themeLink.setAttribute('href', '');
            // 清空打开的页面等数据，但是保存主题数据
            let theme = '';
            if (localStorage.theme) {
                theme = localStorage.theme;
            }
            let companyid = '';
            if(localStorage.companyID){
                companyid = localStorage.companyID;
            }
            localStorage.clear();
            if (theme) {
                localStorage.theme = theme;
            }
            if (companyid) {
                localStorage.companyID = companyid;
            }
        }
    }
};

export default user;
