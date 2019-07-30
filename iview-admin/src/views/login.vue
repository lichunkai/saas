<style lang="less">
    @import './login.less';
</style>

	<template>
		<div class="login" @keydown.enter="handleSubmit" @contextmenu="noclick">
			
				<div class="login-con" v-if="is_license">
					<div class="login-image">
						<img src="../../../frontend/web/yijukelogo.png">
					</div>
					
						<div class="form-con">
							<Form ref="loginForm" :model="form" :rules="rules">
								<FormItem prop="companyId">
									<Select v-model="form.companyId" filterable placeholder="请选择您的公司">
										<Option v-for="item in companyData" :value="item.company_id" :key="item.company_id">{{ item.company_title }}</Option>
									</Select>
								</FormItem>
								<FormItem prop="userName">
									<Input v-model="form.userName" placeholder="请输入用户名">
									</Input>
								</FormItem>
								<FormItem prop="password">
									<Input type="password" v-model="form.password" placeholder="请输入密码">
									</Input>
								</FormItem>
								<FormItem style="margin-bottom: 0 !important;">
									<Button @click="handleSubmit" type="primary" long>登录</Button>
								</FormItem>
							</Form>
							<p class="register" @click="showCompany">还没有注册吗？<a style="text-decoration:none;">公司开户</a></p>
							<p class="login-tip">客户服务电话:158-5003-4101</p>
									
						</div>
				</div>
            <Modal v-model="license"  title="申请授权" :rules="licenseValidate" :closable='false'  :mask-closable=false :width="500" >
                <div slot="header">
                    <div class="ivu-modal-header-inner">申请授权</div>
                </div>
                <div slot="footer">
                    <Button type="primary" size="large" @click="ModalOk">确定</Button>
                </div>
                <Form ref="licenseData" :model="licenseData" :rules="licenseValidate" :label-width="80">
                    <Row>
                        <Col :lg="24" :md="24">
                        <FormItem label="申请门店" prop="mendian">
                            <Input  v-model="licenseData.mendian"  placeholder="申请门店"></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col :lg="24" :md="24">
                        <FormItem label="申请人" prop="shenqingren">
                            <Input  v-model="licenseData.shenqingren"  placeholder="申请人"></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col :lg="24" :md="24">
                        <FormItem label="说明" prop="remake">
                            <Input  v-model="licenseData.remake" type="textarea" :rows="4" placeholder="说明"></Input>
                        </FormItem>
                        </Col>
                    </Row>
                </Form>
            </Modal>
            <Modal v-model="companyRegister"  title="公司开户" :closable='true'  :mask-closable=false :width="460" >
                <div slot="header">
                    <div class="ivu-modal-header-inner">公司开户</div>
                </div>
                <div slot="footer">
                    <Button size="large" @click="companyRegisterCancel">取消</Button>
                    <Button type="primary" size="large" @click="companyRegisterOk">确定</Button>
                </div>
                <Form ref="companyRegisterForm" :model="companyRegisterForm" :rules="companyRegisterValidate" :label-width="80">
                    <Row>
                        <Col :lg="22" :md="22">
                        <FormItem label="公司名称" prop="name">
                            <Input  v-model="companyRegisterForm.name"  placeholder="公司全称"></Input>
                        </FormItem>
                        </Col>
                    </Row>
					 <Row>
					    <Col :lg="22" :md="22">
					    <FormItem label="公司简称" prop="short_name">
					        <Input  v-model="companyRegisterForm.short_name"  placeholder="公司简称"></Input>
					    </FormItem>
					    </Col>
					</Row>
                    <Row>
                        <Col :lg="22" :md="22">
                        <FormItem label="用户名" prop="mobile" >
                            <Input v-model="companyRegisterForm.mobile"  placeholder="用户名"  ></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col :lg="22" :md="22">
                        <FormItem label="登录密码" prop="password">
                            <Input type="password"  v-model="companyRegisterForm.password"  placeholder="登录密码" ></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col :lg="22" :md="22">
                        <FormItem label="确认密码" prop="repassword">
                            <Input type="password"  v-model="companyRegisterForm.repassword"  placeholder="确认登录密码"></Input>
                        </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col :lg="22" :md="22">
                        <FormItem label="验证码" prop="code">
                            <Row>
                                <Col :lg="16" :md="16">
                                <Input type="text" v-model="companyRegisterForm.code" placeholder="验证码"></Input>
                                </Col>
                                <Col :lg="6" :md="6" v-if="waittime==60">
                                <Button type="primary" @click="sendMessage">{{sendtext}}</Button>
                                </Col>
                                <Col :lg="6" :md="6" v-else>
                                <Button type="primary" disabled>{{sendtext}}</Button>
                                </Col>
                            </Row>
                        </FormItem>
                        </Col>
                    </Row>
					<Row style="text-align: center;">
						<Checkbox v-model="single" @on-change="changesck"></Checkbox>我已同意<span class="yonghuxieyi" @click="xieyiModal = true">《宜居客用户服务协议》</span>及<span @click="yinsiModal = true" class="yonghuxieyi">《宜居客平台隐私政策》</span>
					</Row>
                </Form>
            </Modal>
			<Modal v-model="xieyiModal" title="宜居客用户协议" width="960" class="xieyiModal">
				<p>欢迎您使用宜居客的服务！</p>
				<p>为使用宜居客的服务，您应当阅读并遵守本《宜居客用户服务协议》（以下简称“本协议”）。本协议是用户与宜居客之间的法律协议，是用户注册宜居客账号并使用宜居客平台服务或非经注册程序直接使用宜居客平台服务时的通用条款。如果您不同意本协议的约定，您应立即停止注册程序或停止使用宜居客平台服务；如您继续访问和使用宜居客平台服务，即视为您已确知并完全同意本协议各项约定。</p>
				<p class="xieyititle">一、定义</p>
				<p>1、宜居客平台，是指苏州宜居客网络科技有限公司旗下运营之宜居客网站（www.yijuke.com)、宜居客无线站点（m.yijuke.com）及宜居客移动应用软件（APP）。宜居客是一家多平台融合的互联网房产交易平台。作为迅速崛起的行业新生力量，宜居客通过互联网资源整合重构与再生，为开发商、经纪公司、买房卖房者搭建一个高效的、可信赖的新型房产电商平台。</p>
				<p>2、用户，以下亦称为“您”，系指注册或登录宜居客平台使用宜居客产品或服务的具有完全民事权利能力和行为能力的企业法人、社会团体、自然人或其他社会组织等。按用户是否经过注册程序分为注册用户和非注册用户，注册用户是指通过宜居客平台完成全部注册程序后，使用宜居客平台服务或宜居客网站资料的用户。非注册用户是指未进行注册、直接登录宜居客平台或通过其他宜居客平台允许的方式进入宜居客平台直接或间接地使用宜居客平台服务或宜居客网站资料的用户。按用户使用宜居客服务的方式分为开发商用户、经纪人用户以及其他普通消费者用户，其中开发商用户及经纪人用户可能会使用宜居客另行提供的后台系统及服务。</p>
				<p>3、协议方，本协议中协议双方合称“协议方”。</p>
				<p class="xieyititle">二、协议的效力</p>
				<p>1、在您按照注册页面提示填写信息、阅读并同意本协议并完成全部注册程序后或以其他宜居客允许的方式实际使用宜居客平台服务时，您即受本协议的约束。</p>
				<p>2、您承诺接受并遵守本协议的约定。如果您不同意本协议的约定，您应立即停止注册程序或停止使用宜居客平台服务；如您继续访问和使用宜居客平台服务，即视为您已确知并完全同意本协议各项内容。</p>
				<p>3、宜居客有权根据国家法律法规的更新、产品和服务规则的调整需要不时地制订、修改本协议及/或各类规则，并以网站公示的方式进行公示。如您继续使用宜居客平台服务的，即表示您接受经修订的协议和规则。如发生有关争议时，以宜居客最新的相关协议和规则为准。</p>
				<p class="xieyititle">三、账号注册、使用</p>
				<p>1、您确认，在您完成注册程序或以其他宜居客允许的方式实际使用宜居客平台服务时，您应当是具备相应民事行为能力的自然人、法人或其他组织。若您不具备前述主体资格，则您及您的家长或法定监护人（以下统称"监护人"）应承担因此而导致的一切后果，且宜居客有权注销您的账户，并向您及您的监护人索偿。</p>
<p>2、在您签署本协议，完成注册程序时，您的手机号就是对应的唯一编号的宜居客账户。您应对您的用户账户、验证码的安全以及对通过您的账户实施的行为负责，因此所衍生的任何损失或损害，宜居客无法也不承担任何责任。除非有法律规定或行政司法机关的指令，且征得宜居客的同意，否则您的用户账户不得以任何方式转让、借用、赠与或继承（与账户相关的财产权益除外），您的验证码不得对任何第三方提供或泄露。否则，由此给您（或宜居客、任何第三方）造成的一切损失，概由您自行承担（或者负责赔偿），宜居客不承担任何责任。</p>
<p>3、用户在注册时，应使用健康规范符合法律法规及网络文化的用户名，用户名中不得含有任何威胁、恐吓、谩骂、庸俗、亵渎、色情、淫秽、非法、前后矛盾、攻击性、伤害性、骚扰性、诽谤性、辱骂性的或侵害他人知识产权的文字。</p>
<p>4、您在注册帐号或使用宜居客平台服务的过程中，可能需要填写一些必要的信息。如您是经纪人用户，为保证您可以正常发布房源信息、使用隐私通话服务、参与担保房源推广以及宜居客为经纪人用户提供的其他产品或服务的目的，您需提交您真是有效的个人身份信息（如身份证、手机号、名片、经纪人信息卡等）进行实名认证；如您是非经纪人用户的，如您参与宜居客提供的看房团、动态订阅、金融贷款信息咨询、问答、点评等宜居客为用户提供的其他产品或服务的，您需提交您的个人身份信息（如手机号）进行实名认证；其他个人身份信息包括但不限于您的真实姓名、工作单位、住址等具体以宜居客平台页面公示要求为准，如您未能提供您的个人信息的，您将可能无法正常使用宜居客的产品或服务或在使用过程中受到限制。</p>
<p>5、您在注册帐号或使用宜居客平台服务的过程中，应提供合法、真实、准确的个人资料，您的个人资料有变动的，应及时进行更新并向宜居客提供最新的身份资质证明资料供宜居客审核。如果因您提供的个人资料不合法、不真实、不准确的，或故意以虚假无效资料逃避宜居客平台的审核后又变更的，您需承担因此引起的相应责任及后果，并且宜居客保留终止您使用宜居客各项服务的权利。</p>
<p>6、宜居客承诺非经法定原因、本协议的约定或您的事先许可，宜居客不会向任何第三方透露您的注册账号、姓名、身份证号、手机号码等非公开个人信息。如果发现任何人不当使用您的账户或有任何其他可能危及您的账户安全的情形时，您应当立即以有效方式通知宜居客，要求宜居客暂停相关服务。您理解宜居客对您的请求采取行动需要合理时间，宜居客对在采取行动前已经产生的后果（包括但不限于您的任何损失）不承担任何责任。</p>
<p>7、您了解并同意，如您符合并且遵守本协议条款，在通过宜居客平台完成注册程序之后，即可成为宜居客平台用户。对于您主动提交的相关信息，您授权宜居客及/或宜居客网站运营者及关联服务提供方委托的第三方通过合法渠道（包括但不限于征信机构等）了解、咨询、审查您的个人市场交易风险的真实情况，并据此判断您的风险状况。</p>
<p>8、您不得通过任何手段恶意注册宜居客平台帐号，包括但不限于以牟利、炒作、套现、引流、增加曝光度、以低价吸引客户等目的进行多个账号注册。您亦不得盗用其他用户帐号。、您了解并同意，一经注册用户账号或者使用宜居客平台服务，即视为您同意宜居客及/或其关联公司通过短信、电话的方式向您注册时填写的手机号码发送相应的产品服务广告信息、促销优惠等营销信息或为您提供任何您可能感兴趣的信息；您如果不同意发送，您可以通过相应的退订功能进行退订。</p>
				<p class="xieyititle">四、宜居客平台服务使用规范</p>
				<p>1、通过宜居客平台，您可以按照宜居客的规则发布各种信息。但所发布之信息不得含有如下内容：</p>
<p class="xieyititle-text">1）反对宪法所确定的基本原则，煽动抗拒、破坏宪法和法律、行政法规实施的；</p>
<p class="xieyititle-text">2）煽动危害国家安全、泄露国家秘密、颠覆国家政权，推翻社会主义制度的；</p>
<p class="xieyititle-text">3）煽动分裂国家、破坏国家统一、损害国家荣誉和民族利益的；</p>
<p class="xieyititle-text">4）煽动民族仇恨、民族歧视，破坏民族团结的；</p>
<p class="xieyititle-text">5）捏造或者歪曲事实，散布谣言，扰乱社会秩序的；</p>
<p class="xieyititle-text">6）进行政治宣传或破坏国家宗教政策、宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的；</p>
<p class="xieyititle-text">7）公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的；</p>
<p class="xieyititle-text">8）损害国家机关信誉的；</p>
<p class="xieyititle-text">9）其他违反宪法和法律法规的；</p>
<p>2、在接受宜居客平台服务的过程中，您不得从事下列行为：</p>
<p class="xieyititle-text">1）发表、传送、传播、储存侵害他人知识产权（包括著作权、商标权、专利权等）、品牌形象、商业秘密权等合法权利的内容，包含病毒、木马、定时炸弹等可能对宜居客系统造成伤害或影响其稳定性的内容制造虚假身份以误导、欺骗他人；</p>
<p class="xieyititle-text">2）传送或散布以其他方式实现传送的含有受到知识产权法律保护的图像、相片、软件或其他资料的文件，作为举例（但不限于此）：包括版权或商标法（或隐私权或公开权），除非用户拥有或控制着相应的权利或已得到所有必要的认可；</p>
<p class="xieyititle-text">3）使用任何包含有通过侵犯商标、版权、专利、商业机密或任何一方的其他专有权利的方式利用本“软件”获得的图像或相片的资料或信息；</p>
<p class="xieyititle-text">4）进行危害计算机网络安全的行为，包括但不限于：使用未经许可的数据或进入未经许可的服务器/帐号；未经允许进入公众计算机网络或者他人计算机系统并删除、修改、增加存储信息；未经许可，企图探查、扫描、测试本平台系统或网络的弱点或其它实施破坏网络安全的行为；企图干涉、破坏本平台系统或网站的正常运行，故意传播恶意程序或病毒以及其他破坏干扰正常网络信息服务的行为；伪造TCP/IP数据包名称或部分名称；</p>
<p class="xieyititle-text">5）修改或伪造软件作品运行中的指令、数据、数据包，增加、删减、变动软件 的功能或运行效果，不得将用于上述用途的软件通过信息网络向公众传播或者运营；</p>
<p class="xieyititle-text">6）在未经宜居客书面明确授权前提下，出售、出租、出借、散布、转移或转授权软件和服务或相关的链接或从使用软件和服务或软件和服务的条款中获利，无论以上使用是否为直接经济或金钱收益；</p>
<p class="xieyititle-text">7） 违背宜居客页面公布之活动规则，包括但不限于发布虚假信息、作弊或通过其他手段进行虚假交易。</p>
<p>3、您了解并同意，宜居客有权应政府部门（包括司法及行政部门）的要求，向其提供您在宜居客平台填写的注册信息和发布纪录等必要信息。</p>
<p>4、在宜居客平台上使用宜居客平台服务过程中，您承诺遵守以下约定：
在使用宜居客平台服务过程中实施的所有行为均遵守国家法律、法规等规范文件及宜居客平台各项规则的规定和要求，不违背社会公共利益或公共道德，不损害他人的合法权益，不违反本协议及相关规则。您如果违反前述承诺，产生任何法律后果的，您应以自己的名义独立承担所有的法律责任，并确保宜居客免于因此产生任何损失或增加费用。
不发布国家禁止发布的信息（除非取得合法且足够的许可），不发布涉嫌侵犯他人知识产权或其它合法权益的信息，不发布违背社会公共利益或公共道德、公序良俗的信息，不发布其它涉嫌违法或违反本协议及各类规则的信息。
不对宜居客平台上的任何数据作商业性利用，包括但不限于在未经宜居客事先书面同意的情况下，以复制、传播等任何方式使用宜居客平台站上展示的资料。
不使用任何装置、软件或例行程序干预或试图干预宜居客平台的正常运作或正在宜居客平台上进行的任何活动。您不得采取任何将导致不合理的庞大数据负载加诸宜居客平台网络设备的行动。</p>
<p>5、您了解并同意：
您违反上述承诺时，宜居客有权依据本协议的约定，做出相应处理或终止向您提供服务，且无须征得您的同意或提前通知于您。
根据相关法令的指定或者宜居客平台服务规则的判断，您的行为涉嫌违反法律法规的规定或违反本协议和/或规则的条款的，宜居客有权采取相应措施，包括但不限于直接屏蔽、删除侵权信息、降低您的信用值或直接停止提供服务。</p>
<p>对于您在宜居客平台上实施的行为，包括您未在宜居客平台上实施但已经对宜居客平台及其用户产生影响的行为，宜居客有权单方认定您行为的性质及是否构成对本协议和/或规则的违反，并据此采取相应的处理措施。您应自行保存与您行为有关的全部证据，并应对无法提供充要证据承担不利后果。</p>
<p>对于您涉嫌违反承诺的行为对任意第三方造成损害的，您均应当以自己的名义独立承担所有的法律责任，并应确保宜居客免于承担因此产生的损失或增加的费用。</p>
<p class="xieyititle">五、责任范围和责任限制</p>
<p>1、宜居客负责向您提供宜居客平台服务。但宜居客对宜居客平台服务不作任何明示或暗示的保证，包括但不限于宜居客平台服务的适用性、没有错误、疏漏或适用于某一特定用途。宜居客应采取相应措施保护宜居客平台的安全性、稳定性、持续性、可靠性。</p>
<p>2、宜居客仅向您提供宜居客平台服务，您了解宜居客平台上的信息系用户自行发布，由于海量信息的存在，且宜居客客平台无法杜绝可能存在风险和瑕疵。您应谨慎判断确定相关信息的真实性、合法性和有效性，并注意保留相应的证据以利于维权。如可能，尽量采用网站建议的交易方式进行。</p>
<p>3、宜居客平台与其他的在线使用的互联网网站一样，也会受到各种不良信息、网络安全和网络故障问题的困扰，包括但不限于：</p>
<p class="xieyititle-text">1）其他用户可能会发布诈骗或虚假信息，或者发表有谩骂、诅咒、诋毁、攻击内容的等让人反感、厌恶的内容的非法言论；</p>
<p class="xieyititle-text">2）其他用户可能会发布一些侵犯您或者其他第三方知识产权、肖像权、姓名权、名誉权、隐私权和/或其他合法权益的图片、照片、文字等资料；</p>
<p class="xieyititle-text">3）面临着诸如黑客攻击、计算机病毒困扰、系统崩溃、网络掉线、网速缓慢、程序漏洞等问题的困扰和威胁。
您充分了解并同意：上述的各种不良信息、网络安全和网络故障问题，并不是宜居客或者宜居客平台所导致的问题，由此可能会造成您感到反感、恶心、呕吐等精神损害，或者给您造成其他的损失，概由您自行承担，宜居客无须向您承担任何责任。</p>
<p>4、您同意，在发现本网站任何内容不符合法律规定，或不符合本用户协议规定的，您有义务及时通知宜居客。如果您发现您的个人信息被盗用、您的版权或者其他权利被侵害，请将此情况告知宜居客并同时提供如下信息和材料：</p>
<p class="xieyititle-text">1）侵犯您权利的信息的网址，编号或其他可以找到该信息的细节；</p>
<p class="xieyititle-text">2）您是所述的版权或者其他权利的合法拥有者的权利证明；</p>
<p class="xieyititle-text">3）您的个人信息及有效联系方式，包括真实姓名，地址，电话号码和电子邮件等；</p>
<p class="xieyititle-text">4）您的身份证复印件、营业执照等其他相关资料。经审查得到证实的，我们将及时删除相关信息。我们仅接受邮寄、电子邮件或传真方式的书面侵权通知。情况紧急的，您可以通过客服电话先行告知，我们会视情况采取相应措施。</p>
<p>5、您了解并同意，宜居客不对因下述任一情况而导致您的任何损害赔偿承担责任，包括但不限于利润、商誉、使用、数据等方面的损失或其它无形损失的损害赔偿：</p>
<p class="xieyititle-text">1）使用或未能使用宜居客平台服务；</p>
<p class="xieyititle-text">2）第三方未经批准地使用您的账户或更改您的数据；</p>
<p class="xieyititle-text">3）通过宜居客平台购买、获取任何服务、数据、信息等行为或替代行为或与其他任何第三方搭建线上/线下交易、服务法律关系之间的全部事宜产生的费用及损失；</p>
<p class="xieyititle-text">4）您对宜居客平台服务的误解；</p>
<p class="xieyititle-text">5）因不可抗力或其他因非宜居客的原因而引起的与宜居客平台服务有关的其它损失。</p>
<p>6、您在宜居客上使用第三方提供的产品或服务时，除遵守本协议约定外，还应遵守第三方的用户协议及其他相关规则。宜居客和第三方对可能出现的纠纷在法律规定和约定的范围内各自承担责任。</p>
<p>7、您同意在使用宜居客平台服务过程中显示宜居客自行或由第三方服务商向您发送或提供的推广或宣传信息、资讯、动态等（统称为推广信息，包括商业与非商业信息），其方式和范围可不经向您特别通知而变更。除法律法规明确规定外，您应自行对依该推广信息进行的交易审慎评估理性接受，并对交易后果负责，对用户因依该推广信息进行的交易或前述第三方服务商提供的内容因而遭受的损失或损害，宜居客不承担任何责任。</p>
<p>8、宜居客对下列不可抗力行为免责：信息网络正常的设备维护，信息网络连接故障，电脑、通讯或其他系统的故障，电力故障，罢工，劳动争议，暴乱，起义，骚乱，生产力或生产资料不足，火灾，洪水，风暴，爆炸，战争，政府行为，司法行政机关的命令或第三方的不作为而造成的不能服务或延迟服务。</p>
<p>9、您应当严格遵守本协议及宜居客规则等，因您违反协议或规则的行为给第三方或宜居客造成损失的，您应当承担全部责任。</p>
<p>10、宜居客保留在中华人民共和国大陆地区施行之法律允许的范围内独立决定拒绝服务、关闭用户账户、审查、编辑、清除用户发布内容或取消订单的权利。</p>
<p class="xieyititle">六、协议终止</p>
<p>1、您同意，宜居客基于平台服务的安全性，有权中止向您提供部分或全部宜居客平台服务，暂时冻结您的账户，待安全问题解决后及时恢复，并对中止、冻结及恢复的事实及时通知。如果网站的安全问题是由于您的违法行为引起，宜居客有权终止向您提供部分或全部宜居客平台服务，永久冻结（注销）您的帐户，并有权向您对损失进行索赔。</p>
<p>2、如您对本协议的修改有异议，或对宜居客的服务不满，可以行使如下权利：</p>
<p class="xieyititle-text">1）停止使用宜居客平台的服务；</p>
<p class="xieyititle-text">2）通过客服等渠道告知宜居客停止对您提供服务。结束服务后，您使用宜居客服务的权利立即终止。在此情况下，宜居客没有义务传送任何未处理的信息或未完成的服务给您或任何无直接关系的第三方。</p>
<p>3、您同意，您与宜居客的协议关系终止后，宜居客仍享有下列权利：</p>
<p class="xieyititle-text">1）按法律规定保存您在使用宜居客服务期间的信息发布记录、交易记录及及其他登陆使用操作记录等。</p>
<p class="xieyititle-text">2）您在使用宜居客平台服务期间存在违法行为或违反本协议和/或规则的行为的，宜居客仍可依据本协议向您主张权利、追究责任。</p>
<p class="xieyititle">七、隐私权政策</p>
<p>1、宜居客将在宜居客平台公布并不时修订隐私权条款，隐私权条款构成本协议的有效组成部分。关于用户个人信息的收集、存储、使用等事宜。</p>
<p>2、您知悉并认可：宜居客可能会与第三方合作商向用户提供相关的网络服务，在此情况下，如该第三方同意承担与本网站同等的保护用户隐私的责任，则您同意宜居客有权将您的个人注册信息及注册资料等提供给该第三方。另外，在不透露单个用户隐私资料的前提下，宜居客有权对整个用户数据库进行分析并对用户数据库进行商业上的利用。</p>
<p>3、宜居客将运用各种安全技术和程序建立完善的管理制度来保护您的个人信息，以免遭受未经授权的访问、使用或披露。对此您表示理解和认同。</p>
<p>4、宜居客不会将您的个人信息转移或披露给任何非关联的第三方，除非：</p>
<p class="xieyititle-text">1）相关法律法规或法院、政府机关要求；</p>
<p class="xieyititle-text">2）为完成合并、分立、收购或资产转让而转移；</p>
<p class="xieyititle-text">3）为提供您要求的服务所必需；</p>
<p class="xieyititle-text">4）为您提供任何您可能感兴趣的信息或服务。</p>
<p class="xieyititle-text">5、您知悉并认可：宜居客通过推广或其他方式向您提供链接，使您可以接入第三方服务或网站。您使用该等第三方的服务时，须受该第三方的服务条款及隐私政策约束，您需要仔细阅读其条款。本协议仅适用于宜居客提供的服务器所收集的信息，并不适用于任何第三方提供的服务或第三方的信息使用规则，宜居客对任何第三方使用由您提供的信息不承担任何责任。</p>
<p>6、为保护您的个人信息的安全，宜居客平台采取相应的安全技术措施和程序来保护您的个人信息不被未经授权的访问、使用或泄漏。</p>
<p>7、宜居客不对用户所发布信息的删除或储存失败负责。宜居客并不承诺对用户的存储信息进行无限期保留。</p>
<p>8、宜居客要求各搜索引擎遵循行业规范，即“拒绝 Robots 访问标准”(Robots Exclusion Standard)，否则将视你的抓取行为是对我网站财产权利和知识产权的侵犯，有权通过法律诉讼维护网站利益。</p>
<p class="xieyititle">八、知识产权声明</p>
<p>1、“YIJUKE” 、 “宜居客 ”等为宜居客所在公司及其关联公司的已获授权商标或在申请中商标，受法律保护，任何人不得擅自使用或恶意抢注。凡侵犯本公司版权等知识产权的，宜居客将依法追究其相关法律责任。</p>
<p>2、用户一旦接受本协议，即表明该用户主动将其在任何时间段宜居客平台发表的任何形式的信息内容（包括但不限于用户问答、评价、用户咨询、各类资讯、知识、百科、意见、话题文章等信息内容）的财产性权利等任何可转让的权利，如著作权财产权（包括并不限于：复制权、发行权、出租权、展览权、表演权、放映权、广播权、信息网络传播权、摄制权、改编权、翻译权、汇编权以及应当由著作权人享有的其他可转让权利），不可撤销地转让给宜居客所有，用户在宜居客平台发表的内容构成宜居客平台财产的一部分。如用户在宜居客平台发表的内容系通过与宜居客另行签署了书面协议方式约定了知识产权相关事宜的，用户应遵守该特殊书面协议的约定。用户同意宜居客有权就任何主体侵权而单独提起诉讼。</p>
<p>3、用户通过宜居客平台发布的信息或内容，并不代表宜居客之意见及观点，也不意味着宜居客赞同其观点或证实其内容的真实性。</p>
<p>4、用户通过宜居客平台发布的信息、文字、图片等资料，其真实性、准确性和合法性由信息发布人自行承担并负责。如果以上资料侵犯了第三方的知识产权或其他权利，责任由信息发布者本人承担，权利人可向宜居客提出书面主张要求宜居客平台删除、下架或屏蔽相关侵权内容或链接。</p>
<p>5、除法律另有强制性规定外，未经宜居客明确的特别书面许可，任何单位或个人不得以任何方式非法地全部或部分复制、传播、展示、镜像、上载、下载、转载、引用、链接、抓取或以其他方式使用宜居客平台的信息内容及相关资料，否则，宜居客有权追究其法律责任。</p>
<p>6、宜居客平台以下内容不可任意转载：</p>
<p class="xieyititle-text">1）本平台内发布的所有信息；</p>
<p class="xieyititle-text">2）已作出不得转载或未经许可不得转载声明的内容；</p>
<p class="xieyititle-text">3）本平台中特有的图形、标志、页面风格、编排方式、程序等；</p>
<p class="xieyititle-text">4）本平台中必须具有特别授权或具有注册用户资格方可知晓的内容；</p>
<p class="xieyititle-text">5）其他法律不允许或本平台认为不适合转载的内容。</p>
<p>7、对于不当引用宜居客网站内容而引起的纷争等或因纠纷等造成的任何损失，宜居客不承担相关法律责任。对不遵守本声明的用户或其他违法、恶意使用宜居客平台内容者，宜居客保留追究其法律责任的权利。</p>
<p class="xieyititle">九、法律适用、管辖与其他</p>
<p>1、本协议之订立、生效、解释、修订、补充、终止、执行与争议解决均适用中华人民共和国法律，如法律无相关规定的，则应参照通用国际商业惯例和（或）行业惯例。</p>
<p>2、本协议任一条款被视为废止、无效或不可执行，该条应视为可分的且并不影响本协议其余条款的有效性及可执行性。</p>
<p>3、因本协议产生之争议、纠纷，应由宜居客与您友好协商解决；协商不成的，任何一方均有权向苏州工业园区人民法院诉讼解决。</p>
			</Modal>
			<Modal v-model="yinsiModal" title="宜居客平台隐私政策" width="960" class="yinsiModal">
				<p>欢迎您下载使用宜居客（以下简称“本软件”），宜居客是由苏州宜居客网络科技有限公司（以下简称“本公司”）向用户提供的专业的买房卖房信息平台。为保证您的权益，便于更好地使用宜居客及相应的服务，请您务必在下载使用前认真阅读本协议，若您阅读并接受本协议，使用宜居客提供的产品和服务，即视为您受本协议的约束，若您不同意本协议，请勿使用本产品和服务。</p>

<p>本软件尊重并保护所有使用服务用户的个人隐私权。为了给您提供更准确、更有个性化的服务，本软件会按照本隐私权政策的规定使用和披露您的个人信息。但本软件将以高度的勤勉、审慎义务对待这些信息。除本隐私权政策另有规定外，在未征得您事先许可的情况下，本软件不会将这些信息对外披露或向第三方提供。本软件会不时更新本隐私权政策。您在同意本软件服务使用协议之时，即视为您已经同意本隐私权政策全部内容。本隐私权政策属于本软件服务使用协议不可分割的一部分。</p>

<p class="xieyititle">一、适用范围</p>
<p>1、在您使用本软件网络服务，本软件自动接收并记录的您的手机上的信息，包括但不限于您的健康数据、使用的语言、访问日期和时间、软硬件特征信息及您需求的网页记录等数据；</p>

<p class="xieyititle">二、信息的使用</p>
<p>1、在获得您的数据之后，本软件会将其上传至服务器，以生成您的浏览数据，以便您能够更好地使用服务。</p>

<p class="xieyititle">三、信息披露</p>
<p>1、本软件不会将您的信息披露给不受信任的第三方。</p>
<p>2、根据法律的有关规定，或者行政或司法机构的要求，向第三方或者行政、司法机构披露；</p>
<p>3、如您出现违反中国有关法律、法规或者相关规则的情况，需要向第三方披露；</p>
<p>4、信息存储和交换本软件收集的有关您的信息和资料将保存在本软件及（或）其关联公司的服务器上，这些信息和资料可能传送至您所在国家、地区或本软件收集信息和资料所在地的境外并在境外被访问、存储和展示。</p>

<p class="xieyititle">四、信息安全</p>
<p>1、在使用本软件网络服务进行网上交易时，您不可避免的要向交易对方或潜在的交易对方披露自己的个人信息，如联络方式或者邮政地址。请您妥善保护自己的个人信息，仅在必要的情形下向他人提供。如您发现自己的个人信息泄密，请您立即联络本软件客服，以便本软件采取相应措施。</p>

<p class="xieyititle">五、免责条款
<p>1、本软件旨在为客户提供交易和信息查询服务，不对您个人损失负责。</p>
<p>2、由于电信运营商提供的通信线路等原因造成的以及由不可抗力造成的暂时性不能或者部分不能提供服务的，本软件不承担任何责任。</p>
<p>3、任何由于黑客攻击、计算机病毒侵入或发作、因政府管制而造成的暂时性关闭等影响网络正常经营的不可抗力而造成的个人资料泄露、丢失、被盗用或被窜改等，本软件应及时采取补救措施，不承担任何责任。</p>
			</Modal>
        </div>
    </template>


    <script>
        import Cookies from 'js-cookie';
        export default {
            data () {
                const validatePassCheck = (rule, value, callback) => {
                    if (value !== this.companyRegisterForm.password) {
                        callback(new Error('两次输入密码不一致!'));
                    } else {
                        callback();
                    }
                };
                const validateMobile = (rule, value, callback) => {
                    if (!/^1[345789]\d{9}$/.test(value)) {
                        callback(new Error('请输入正确的手机号码'));
                    }
                    callback();
                };
                return {
					single: false,
					xieyiModal: false,
					yinsiModal: false,
                    // 自动检索公司名称
                    companyValue: '',
                    companyData: [],
                    form: {
                        companyId:'',
                        userName: '',
                        password: ''
                    },
                    rules: {
                        companyId: [
                            { required: true, message: '请选择您的公司', trigger: 'change' }
                        ],
                        userName: [
                            { required: true, message: '账号不能为空', trigger: 'blur' }
                        ],
                        password: [
                            { required: true, message: '密码不能为空', trigger: 'blur' }
                        ],
                    },
                    //注册
                    companyRegister:false,
                    companyRegisterForm:{
                        name:'',
						short_name:'',
                        mobile:'',
                        password: '',
                        repassword: '',
                        recommend_code:'',
                        code:''
                    },
                    companyRegisterValidate:{
                        name: [
                            { required: true, message: '请填写公司名称', trigger: 'blur' }
                        ],
						short_name: [
						    { required: true, message: '请填写公司简称', trigger: 'blur' }
						],
                        mobile: [
                            { required: true, message: '请填写公司负责人手机号', trigger: 'blur' },
                            {validator: validateMobile, trigger: 'blur'}
                        ],
                        password: [
                            { required: true, message: '请填写登录密码', trigger: 'blur' },
                            {type: 'string', min: 6, max: 10, message: '登录密码长度6-10位', trigger: 'blur'}
                        ],
                        repassword: [
                            {required: true, message: '请再次填写登录密码', trigger: 'blur'},
                            {validator: validatePassCheck, trigger: 'blur'}
                        ],
                        code: [
                            { required: true, message: '请填写短信验证码', trigger: 'blur' }
                        ],
                    },
                    sendtext:'发送验证码',
                    waittime: 60,
                    //机器码
                    license:false,
                    is_license:true,
                    licenseData:{},
                    licenseValidate:{
                        mendian: [
                            { required: true, message: '门店不能为空', trigger: 'blur' }
                        ],
                        shenqingren: [
                            { required: true, message: '申请人不能为空', trigger: 'blur' }
                        ],
                        remake: [
                            { required: true, message: '说明不能为空', trigger: 'blur' }
                        ],

                    },
            };
        },
        created(){
             // this.getLicense();
             // let id = window.localStorage.getItem('companyID', this.form.companyId);
             // if(id){
             //     this.$set(this.form,'companyId',id);
             // }
            this.getCompany();

        },
        methods: {
            changesck(){
                this.single = true;
            },
            // 自动检索公司名称
            getCompany () {
                this.$http.get(api_param.apiurl + 'login/companylist', {
                    params: {},
                    headers: {'X-Access-Token': api_param.XAccessToken}
                }).then(function (response) {
                    // 这里是处理正确的回调
                    this.companyData = response.data.data;
                    // console.log(this.companyData);
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response);
                })
            },
            noclick(){
                return false;
            },
            //注册公司账户
            showCompany(){
                this.companyRegister = true;
            },
            companyRegisterCancel(){
                this.companyRegister = false;
                this.$refs['companyRegisterForm'].resetFields();
            },
            companyRegisterOk(){
                if(this.single != true){
                    this.$Message.warning('请勾选用户协议和宜居客平台隐私政策');
                    return;
                }
                this.$refs['companyRegisterForm'].validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'company/register',
                            {
                                'name': this.companyRegisterForm.name,
								'short_name': this.companyRegisterForm.short_name,
                                'phone': this.companyRegisterForm.mobile,
                                'password': this.companyRegisterForm.password,
                                'recommend_code':this.companyRegisterForm.recommend_code,
                                'code': this.companyRegisterForm.code,
                            },
                            {emulateJSON: true}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if (response.data.code == 200) {
                                this.companyRegister = false;
                                this.$refs['companyRegisterForm'].resetFields();
                                this.$Notice.success({
                                    title: '开户成功通知',
                                    desc: response.data.data.content,
                                });
                                this.$set(this.form,'companyId',response.data.data.companyid);
								this.getCompany();
                            } else {
                                this.$Message.warning(response.data.message);
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response)
                        })
                    }
                });
            },
            sendMessage(){
                this.$http.post(api_param.apiurl + 'company/sendsms',
                    {
                        'phone':this.companyRegisterForm.mobile
                    },
                    {emulateJSON: true}
                ).then(function (response) {
                    // 这里是处理正确的回调
                    if(response.data.code == '200'){
                        this.time();
                    }else{
                        this.$Message.warning(response.data.message);
                    }
                }, function (response) {
                    // 这里是处理错误的回调
                    console.log(response)
                })
            },
            time() {
                if (this.waittime == 0) {
                    this.sendtext = '发送验证码';
                    this.waittime = 60;
                } else {
                    this.sendtext = "重新发送(" + this.waittime + ")"
                    this.waittime = this.waittime - 1;
                    let _this = this;
                    setTimeout(function () {
                        _this.time();
                    }, 1000);
                }
            },
            handleSubmit () {
                //登录之前需要检查是不是在软件上上的
                // if(!this.getCode()){
                 //    this.$Message.error('机器授权失败');
                 //    return false;
                // }
                this.$refs.loginForm.validate((valid) => {
                    if (valid) {
                        this.$http.post(api_param.apiurl + 'login/login',
                            {
                                'cid':this.form.companyId,
                                'account': this.form.userName,
                                'password': this.form.password,
                            },
                            {emulateJSON: true}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if(response.data.code == '200'){
                                this.$Message.success(response.data.message);
                                Cookies.set('user', response.data.data.u_name);
                                Cookies.set('u_mobile', response.data.data.u_phone);
                                Cookies.set('companyid', response.data.data.company_id);
                                Cookies.set('dts_id', response.data.data.dts_id);
                                Cookies.set('area_id', response.data.data.area_id);
                                Cookies.set('area', response.data.data.area_name);
                                Cookies.set('district', response.data.data.dts_name);
                                Cookies.set('uid', response.data.data.u_id);
                                Cookies.set('u_dept_id', response.data.data.u_dept_id);
                                Cookies.set('dept_name', response.data.data.d_name);
                                Cookies.set('role_type', response.data.data.role_type);
                                //清除打开的tag标签
                                this.$store.commit('clearAllTags');
                                this.$store.commit('setAvator', 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3448484253,3685836170&fm=27&gp=0.jpg');
                                api_param.XAccessToken = response.data.data.tokens;
                                window.localStorage.setItem('userTokens', response.data.data.tokens);
                                window.localStorage.setItem('companyID', this.form.companyId);
                                this.$store.commit('addMenulist',response.data.data.menu);
                                this.$router.push({
                                    name: 'home_index'
                                });
                            }else{
                                this.$Message.warning(response.data.message);
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response)
                        })
                    }
                });
            },
            //查看机器授权
            getLicense(){
                let code = this.getCode();
                if(code){
                    this.$http.post(api_param.apiurl + 'login/getlicense',
                        {'code': code,},
                        {emulateJSON: true}
                    ).then(function (response) {
                        // 这里是处理正确的回调
                        if(response.data.code == '200'){
                            this.is_license=true;
                        }else{
                            this.license=true;
                            this.$Message.warning(response.data.message);
                        }
                    }, function (response) {
                        // 这里是处理错误的回调
                        console.log(response)
                    })
                }else{
                    this.$Message.error('获取机器码失败，请使用客户端登录系统！');
                    return false
                }

            },
            //获取机器码
            getCode(){
                let code = '';
                try {
                    code = window.HTMLPackHelper.machineCode;
                } catch (e) {
                    return false;
                }
                return code;
            },
            ModalOk(){
                this.$refs['licenseData'].validate((valid) => {
                    if (valid) {
                        this.licenseData.code = this.getCode();
                        this.$http.post(api_param.apiurl + 'login/setlicense',
                            this.licenseData,
                            {emulateJSON: true}
                        ).then(function (response) {
                            // 这里是处理正确的回调
                            if(response.data.code == '200'){
                                this.license=false;
                                this.$Message.success(response.data.message);

                            }else{
                                this.license=true;
                                this.$Message.warning(response.data.message);
                            }
                        }, function (response) {
                            // 这里是处理错误的回调
                            console.log(response)
                        })

                    }})

            }
        }
    };
    </script>

    <style>
	.yonghuxieyi{
		color: #2d8cf0;
		cursor: pointer;
	}
    </style>
