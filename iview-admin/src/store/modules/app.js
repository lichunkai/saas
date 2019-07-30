import {routers,otherRouter, appRouter} from '@/router/router';
import Util from '@/libs/util';
import Cookies from 'js-cookie';
import Vue from 'vue';

const app = {
    state: {
        cachePage: [],
        lang: '',
        isFullScreen: false,
        openedSubmenuArr: [], // 要展开的菜单数组
        menuTheme: 'dark', // 主题
        themeColor: '',
        pageOpenedList: [{
            title: '首页',
            path: '',
            name: 'home_index'
        }],
        currentPageName: '',
        currentPath: [
            {
                title: '首页',
                path: '',
                name: 'home_index'
            }
        ], // 面包屑数组
        menuList: [],
        routers: [
            otherRouter,
            ...appRouter
        ],
        tagsList: [...otherRouter.children],
        messageCount: 0,
        dontCache: ['text-editor', 'artical-publish'] // 在这里定义你不想要缓存的页面的name属性值(参见路由配置router.js)
    },
    mutations: {
        setTagsList (state, list) {
            state.tagsList.push(...list);
        },
        addMenulist (state,menu) {
            let newMenu = eachMenu(appRouter,menu);//appRouter;
            window.localStorage.setItem('sideMenuList',JSON.stringify(newMenu));
            state.menuList = newMenu;
        },
        updateMenulist (state,menu) {
            let newMenu = menu;
            window.localStorage.setItem('sideMenuList',JSON.stringify(newMenu));
            state.menuList = newMenu;
        },
        changeMenuTheme (state, theme) {
            state.menuTheme = theme;
        },
        changeMainTheme (state, mainTheme) {
            state.themeColor = mainTheme;
        },
        addOpenSubmenu (state, name) {
            let hasThisName = false;
            let isEmpty = false;
            if (name.length === 0) {
                isEmpty = true;
            }
            if (state.openedSubmenuArr.indexOf(name) > -1) {
                hasThisName = true;
            }
            if (!hasThisName && !isEmpty) {
                state.openedSubmenuArr.push(name);
            }
        },
        removeTag (state, name) {
            var s=1
            state.pageOpenedList.map((item, index) => {
                if(name[0] && s){
                    if (!item.argu.customer_uuid && item.argu.houseId==name[0]) {
                        state.pageOpenedList.splice(index, 1);
                        s=0
                        return index
                    }else if(!item.argu.houseId && item.argu.customer_uuid==name[0]){
                        state.pageOpenedList.splice(index, 1);
                        s=0
                        return index
                    }else{
                        if(item.name === name[1] &&  !item.argu.houseId && !item.argu.customer_uuid ){
                            state.pageOpenedList.splice(index, 1);
                            s=0
                            return index
                        }
                    }
                }
            });
        },
        closePage (state, name) {

            state.cachePage.forEach((item, index) => {
                if (item === name) {
                    state.cachePage.splice(index, 1);
                }
            });
        },
        initCachepage (state) {
            if (localStorage.cachePage) {
                state.cachePage = JSON.parse(localStorage.cachePage);
            }
        },
        pageOpenedList (state, get) {
            let openedPage = state.pageOpenedList[get.index];
            if (get.argu) {
                openedPage.argu = get.argu;
            }
            if (get.query) {
                openedPage.query = get.query;
            }
            state.pageOpenedList.splice(get.index, 1, openedPage);
            localStorage.pageOpenedList = JSON.stringify(state.pageOpenedList);
        },
        clearAllTags (state) {
            state.pageOpenedList.splice(1);
            state.cachePage.length = 0;
            localStorage.pageOpenedList = JSON.stringify(state.pageOpenedList);
        },
        clearOtherTags (state, vm) {
            let currentName = vm.$route.name;
            let currentIndex = 0;
            state.pageOpenedList.forEach((item, index) => {
                if (item.name === currentName) {
                    currentIndex = index;
                }
            });
            if (currentIndex === 0) {
                state.pageOpenedList.splice(1);
            } else {
                state.pageOpenedList.splice(currentIndex + 1);
                state.pageOpenedList.splice(1, currentIndex - 1);
            }
            let newCachepage = state.cachePage.filter(item => {
                return item === currentName;
            });
            state.cachePage = newCachepage;
            localStorage.pageOpenedList = JSON.stringify(state.pageOpenedList);
        },
        setOpenedList (state) {
            state.pageOpenedList = localStorage.pageOpenedList ? JSON.parse(localStorage.pageOpenedList) : [otherRouter.children[0]];
        },
        setCurrentPath (state, pathArr) {
            state.currentPath = pathArr;
        },
        setCurrentPageName (state, name) {
            state.currentPageName = name;
        },
        setAvator (state, path) {
            localStorage.avatorImgPath = path;
        },
        switchLang (state, lang) {
            state.lang = lang;
            Vue.config.lang = lang;
        },
        clearOpenedSubmenu (state) {
            state.openedSubmenuArr.length = 0;
        },
        setMessageCount (state, count) {
            state.messageCount = count;
        },
        increateTag (state, tagObj) {
            if (!Util.oneOf(tagObj.name, state.dontCache)) {
                state.cachePage.push(tagObj.name);
                localStorage.cachePage = JSON.stringify(state.cachePage);
            }
            state.pageOpenedList.push(tagObj);
            localStorage.pageOpenedList = JSON.stringify(state.pageOpenedList);
        }
    }
};
function eachMenu(menu = [], compare = [], menulist = []) {
    if (menu.length <= 0 || compare.length <= 0) return menulist;
    for (let item of menu) {
        compare.forEach(inItem => {
            if (inItem.name === item.name) {
                let arr = {
                    path: item.path,
                    icon: item.icon,//inItem.icon ? inItem.icon : item.icon,
                    name: item.name,
                    title: item.title ? item.title : inItem.title
                };
                arr.children = [];
                if (item.children && item.children.length !== 0) {
                    arr.children = eachMenu(item.children, compare);
                }
                menulist.push(arr);
            }
        });
    }//console.log(menulist);
    return menulist;
}
export default app;
