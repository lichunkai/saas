// 配置接口全局变量

 // const domain = 'http://47.103.50.82:91/';
 // const domain = 'http://saas.yijuke.net/';
// const domain = 'http://47.103.50.82:8000/';
const domain = 'http://admin.saas.com/';
const XAccessToken= window.localStorage.getItem('userTokens');
const imgdomain = 'http://img.saas.com/';
// const imgdomain = 'http://47.103.50.82:8001/';
const newHouseUrl = 'http://fangchan.szglweb.com';
const newHouseKey='C34D18F06AE07AA2F5DBD4936DC254D6';
const newHouseTime=Math.round(new Date().getTime()/1000).toString();

// const domain = 'http://admin.zh2.com/';
// const XAccessToken= window.localStorage.getItem('userTokens');
// const imgdomain = 'http://img.zh2.com';

// const domain = 'http://saas.ihs.pw/';
// const XAccessToken= window.localStorage.getItem('userTokens');
// const imgdomain = 'http://img.saas.ihs.pw/';


//const domain = 'https://saas.gengleisz.com/index.php/';
//const XAccessToken = window.localStorage.getItem('userTokens');
//const imgdomain = 'http://img.saas.gengleisz.com/';






export default {
    apiurl: domain,
    imgurl: imgdomain,
    XAccessToken: XAccessToken,
    newHouseUrl: newHouseUrl,
    newHouseKey: newHouseKey,
    newHouseTime: newHouseTime
};
