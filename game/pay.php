<?php
/*
 * 元宝支付 http://pay.zy13.net
 * Date: 2020/12/10
 * Time: 15:38
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究接口使用，只是提供一个参考，最终解释权归美奇软件开发工作室所有。
 * 严禁一切钓鱼、色情、赌博、私彩及违反国家法律法规等使用。
 * 注意：
 * UTF-8编码不要在记事本下编辑，否则会出现一些奇葩的问题，正确方法应在开发工具里打开编辑
 */
ini_set("error_reporting","E_ALL & ~E_NOTICE");
header('Content-Type:application/json; charset=utf-8');
require "Des.php";
/////////////////需要修改的参数/////////////////
//商户编号
$appid = '20206491';
//商户密钥
$appkey = '7FAd7Bb448dc2909cE55769D5Eef8F27';
//算法密钥
$deskey='qDHPJKO!';  
//数据库相关
define('MYSQLHOST', '127.0.0.1');//数据库地址
define('MYSQLPORT', 3306);       //数据库端口
define('MYSQLUSER', 'root');     //数据库用户名
define('MYSQLPASS', 'xiaomei520@com');   //数据库密码
///////////////////////////////////////////////
//下面代码基本不需要修改
if (empty($_POST)) $_POST = $_GET;  //如果为GET方式访问
if(!isset($_POST['appid'])){
	outputJson([
		'code' => 0,
		'msg'  => '参数非法'
	]);
}
//验证商户号
if($_POST['appid']!=$appid){
	outputJson([
		'code' => 0,
		'msg'  => '商户编号不正确'
	]);
}
if (!function_exists('openssl_encrypt')){
	outputJson([
		'code' => 0,
		'msg'  => '请开启php.ini的openssl扩展'
	]);
}
//解密接口参数
$des = new Des();
$parameter=$_POST['parameter'];
$decode=$des->decrypt($parameter,$deskey);
if(empty($decode)){
	outputJson([
		'code' => 0,
		'msg'  => '算法密钥不正确'
	]);
}
parse_str($decode, $data);
//验证商户密钥
if($appkey!=$data['appkey']){
	outputJson([
		'code' => 0,
		'msg'  => '商户密钥不正确'
	]);
}

//开始充值
chongzhi($data);
function chongzhi($data){
    //游戏充值代码
    $account = $data['account'];
	$money = $data['money'];
	$alias = $data['alias'];
	$scale = $data['scale'];
	$database = $data['database'];
    $quid = $data['quid']; 
	$quname = $data['quname'];
	$gold=$money*$scale;//元宝=充值金额*充值比例
    $conn=@mysql_connect(MYSQLHOST,MYSQLUSER,MYSQLPASS,MYSQLPORT) or die("数据库连接失败,请联系管理员！");
    //mysql_query("set names 'utf8'");
    mysql_select_db($database,$conn);
    $result=mysql_query("SELECT accountname,actorid FROM actors WHERE accountname = '$account'");//SQL语句
    if($result&&mysql_num_rows($result)>0){
        $row = mysql_fetch_array($result);
        $accountname=$row[0];
        $actorid=$row[1];
        mysql_query("insert into feecallback(serverid,openid,itemid,actor_id) values ('$quid','$accountname','$gold',$actorid)");
        //成功后返回
		$arr['code']=1;
		$text = $gold>=10000?($gold/10000).'万':$gold;
		$arr['msg']= sprintf("[%s]账号%s成功充值金额%s元，获得%s：%s个",$quname,$account,$money,$alias,$text);
    }else{      
        $arr['code']=0;
		$arr['msg']="充值失败，帐号".$account."在[".$quname."]还没有角色";
    }
	mysql_close($conn);
	//输出json信息
	outputJson($arr);
}
function outputJson($arr){
	echo json_encode($arr, JSON_UNESCAPED_UNICODE);
	exit;
}
?>