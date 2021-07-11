<?php
namespace app\common\dingding;

class DingdingSdk 
{
	//消息类型
	public $msgtype = array('text',"link","markdown","actionCard","feedCard");
	//密钥：加标签时需要
	public $secret ='';
	public function __construct()
	{}
	
	/**
	 * @param $webhook:发送的URL地址
	 * @param $data:发送的数据（数组）
	 * @param $type:消息类型,默认为text
	 * @param $set:安全设置的加密类型,默认为空，(只有安全设置在加签时才传值sign)
	 */
	public function send($webhook,$data,$type ='text',$set='')
	{
		if(!in_array($type, $this->msgtype))
		{
			dump(array('code' => 0, 'msg' => '消息类型不对', 'data' =>'' ));
			return json_encode(array('code' => 0, 'msg' => '消息类型不对', 'data' =>'' ));
		}
		//安全设置(加签)
		if($set=='sign'){
			$timestamp = microtime();
			$string = $timestamp."\n".$this->secret;
			$s = base64_encode(hash_hmac( 'sha256', $string, $this->secret, true));
			$sign=urlencode($s);
			$webhook = $webhook.'&timestamp='.$timestamp.'&sign='.$sign;
		}
		if(is_array($data))
		{
			$data = json_encode($data);
		}
		$result = $this->request_by_curl($webhook, $data);
		$result_arr = json_decode($result,true);
		if($result_arr['errcode']==0)
		{
			return json_encode(array('code' => 1, 'msg' => '消息发送成功:'.$result_arr['errmsg'], 'data' =>'' ));
		}else{
			dump(array('code' => 0, 'msg' => '消息发送失败:'.$result_arr['errmsg'], 'data' =>'' ));
			return json_encode(array('code' => 0, 'msg' => '消息发送失败:'.$result_arr['errmsg'], 'data' =>'' ));
		}
	}
	function request_by_curl($remote_server, $post_string)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $remote_server);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$data = curl_exec($ch);
		//var_dump($data);
		curl_close($ch);
		return $data;
	}
}