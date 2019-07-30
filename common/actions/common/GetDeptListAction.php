<?php
/**
 * Created by PhpStorm.
 * User: daiyu
 * Date: 2017/12/26
 * Time: 16:59
 */

namespace common\actions\common;

use backend\models\YsDept;
use common\helps\Tools;
use common\models\ApiReturn;
use Yii;
use yii\base\Action;
use yii\helpers\Html;

class GetDeptListAction extends Action
{
    public function run()
    {
        $model = new YsDept();
        $dept = $model::find()->select('dept_id as id,dept_name as name,dept_pid as pId,dept_icon as icon')->where(['is_del' => 0])->orderBy('ctime desc')->asArray()->all();
        // 创建Tree
//        $tree = array();
//        if(is_array($dept)) {
//            // 创建基于主键的数组引用
//            $refer = array();
//            foreach ($dept as $key => $data) {
//                $refer[$data['id']] =& $dept[$key];
//            }
//            foreach ($dept as $key => $data) {
//                // 判断是否存在parent
//                $parentId =  $data['dept_pid'];
//                if (0 == $parentId) {
//                    $tree[] =& $dept[$key];
//                }else{
//                    if (isset($refer[$parentId])) {
//                        $parent =& $refer[$parentId];
//                        $parent['children'][] =& $dept[$key];
//                    }
//                }
//            }
//        }
        //var_dump($tree);die;
        return ApiReturn::success('获取成功',$dept);//json_encode($tree);
    }
}

//[ //节点
//            {
//                name: '常用文件夹'
//                ,id: 1
//                ,alias: 'changyong'
//                ,children: [
//                {
//                    name: '所有未读（设置跳转）'
//                    ,id: 11
//                    ,alias: 'weidu'
//                }, {
//                name: '置顶邮件'
//                    ,id: 12
//                }, {
//                name: '标签邮件'
//                    ,id: 13
//                }
//            ]
//            }, {
//    name: '我的邮箱'
//                ,id: 2
//                ,spread: true
//                ,children: [
//                    {
//                        name: 'QQ邮箱'
//                        ,id: 21
//                        ,spread: true
//                        ,children: [
//                        {
//                            name: '收件箱'
//                            ,id: 211
//                            ,children: [
//                            {
//                                name: '所有未读'
//                                ,id: 2111
//                            }, {
//                            name: '置顶邮件'
//                                ,id: 2112
//                            }, {
//                            name: '标签邮件'
//                                ,id: 2113
//                            }
//                        ]
//                        }, {
//                        name: '已发出的邮件'
//                            ,id: 212
//                        }, {
//                        name: '垃圾邮件'
//                            ,id: 213
//                        }
//                    ]
//                    }, {
//        name: '阿里云邮'
//                        ,id: 22
//                        ,children: [
//                            {
//                                name: '收件箱'
//                                ,id: 221
//                            }, {
//            name: '已发出的邮件'
//                                ,id: 222
//                            }, {
//            name: '垃圾邮件'
//                                ,id: 223
//                            }
//                        ]
//                    }
//                ]
//            }
//        ]