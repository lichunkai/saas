<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "org_company".
 *
 * @property string $company_id 机构id
 * @property int $company_type 机构类型-1:中介；2:开发商
 * @property int $company_level 机构级别1:青铜会员2:白银会员3:黄金会员4:白金会员5:荣耀会员6:至尊会员
 * @property string $company_account 机构账号，用于登录
 * @property string $company_title 机构名称
 * @property string $recommend_code 推荐码
 * @property string $logo 机构的logo
 * @property string $business_license 营业执照
 * @property string $tax_license 税务登记证
 * @property string $certificate_license 经营许可证
 * @property string $app_id 公司小程序appid
 * @property string $secret 公司小程序秘钥
 * @property string $gzh_app_id 微信公众号appid
 * @property string $gzh_secret 微信公众号秘钥
 * @property string $phone 机构电话
 * @property string $tel 座机
 * @property string $email 邮箱
 * @property int $province_id 省id
 * @property string $province_title 省名称
 * @property int $city_id 市id
 * @property string $city_title 市名称
 * @property int $district_id 区id
 * @property string $district_title 区名称
 * @property string $address 详细地址
 * @property string $intro 机构简介
 * @property string $invoice_name 单位名称（开发票使用）
 * @property string $invoice_number 纳税人识别号（开发票使用）
 * @property string $invoice_address 单位地址（开发票使用）
 * @property string $invoice_phone 联系电话（开发票使用）
 * @property int $status 状态:0:禁用，1:启用 ，2:封禁
 * @property int $cid 创建人
 * @property int $uid 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除（0=否  1=是）
 */
class OrgCompanyGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_type', 'company_level', 'province_id', 'city_id', 'district_id', 'status', 'cid', 'uid', 'is_del'], 'integer'],
            [['intro'], 'string'],
            [['status'], 'required'],
            [['ctime', 'utime'], 'safe'],
            [['company_account', 'phone', 'tel'], 'string', 'max' => 16],
            [['company_title', 'logo', 'address'], 'string', 'max' => 160],
            [['recommend_code'], 'string', 'max' => 30],
            [['business_license', 'tax_license', 'certificate_license'], 'string', 'max' => 255],
            [['app_id', 'secret', 'gzh_app_id', 'gzh_secret', 'invoice_name', 'invoice_number'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 64],
            [['province_title', 'city_title', 'district_title', 'invoice_address'], 'string', 'max' => 100],
            [['invoice_phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'company_id' => '机构id',
            'company_type' => '机构类型-1:中介；2:开发商',
            'company_level' => '机构级别1:青铜会员2:白银会员3:黄金会员4:白金会员5:荣耀会员6:至尊会员',
            'company_account' => '机构账号，用于登录',
            'company_title' => '机构名称',
            'recommend_code' => '推荐码',
            'logo' => '机构的logo',
            'business_license' => '营业执照',
            'tax_license' => '税务登记证',
            'certificate_license' => '经营许可证',
            'app_id' => '公司小程序appid',
            'secret' => '公司小程序秘钥',
            'gzh_app_id' => '微信公众号appid',
            'gzh_secret' => '微信公众号秘钥',
            'phone' => '机构电话',
            'tel' => '座机',
            'email' => '邮箱',
            'province_id' => '省id',
            'province_title' => '省名称',
            'city_id' => '市id',
            'city_title' => '市名称',
            'district_id' => '区id',
            'district_title' => '区名称',
            'address' => '详细地址',
            'intro' => '机构简介',
            'invoice_name' => '单位名称（开发票使用）',
            'invoice_number' => '纳税人识别号（开发票使用）',
            'invoice_address' => '单位地址（开发票使用）',
            'invoice_phone' => '联系电话（开发票使用）',
            'status' => '状态:0:禁用，1:启用 ，2:封禁',
            'cid' => '创建人',
            'uid' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除（0=否  1=是）',
        ];
    }
}
