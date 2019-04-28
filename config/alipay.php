<?php  
return [	
		//应用ID,您的APPID。
		'app_id' => "2016092700605422",

		//商户UID
		'seller_id'=>'2088102177451808',

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEA04ZwhF1UK75QXLf31MCOE97rfMFaZW5oAhAhgiAc0cuG6vuE8iyNGF0JNC19oXGHPObnR3KSxxv/kZkhFnXRRmeyWzqovLOfG1lsgR9p6Mk4NM3LVTVgFtMUw3YD/8IHeLiXIPq4HNFIMwDW8gzyTuHn2NWHJOzlaWZaorEK1XcTnSjxQNsaDhjEC5uBTJNI8SRa3HgQg+t3h3kX4/PRYDlIhgx6trui54fEj1F1MHyEVZtYo5H3H+vYN4aX8f3HE5yreAFBftXPAryNsk7VPfXhTJMK6/zEtHQ16SJB1/BlTvYSzDpuVRYELE2HECtsbhVHVp0p+PUlTiHyBOGCiQIDAQABAoIBAQCmfn6YIWH8YHuez4t6YeozR9bxxruhdSrW3N2TMDWfCOV0lm+Js3mHwMtaz6fAaLz7OgC3oz5XDPm4H+EEbc5A7aRc3Kouhe/pthSK+jAB9EiSf53Zvv6YWKNSStVQ/eelpR4H7nD29eAvjSyjL3+WiYhVi5UpgnH86XzT7CjL5dQ61BRcjhBHmstIBlgIdadqVLzSHCRCw6DIBmqNVoxz9jN5r+WNAtwrsKf7rfPEcEgiRW/C7D9vB8YTciFYwclKRqOPslT3I8bsELP6G4vKCutdADkXIexZiUjpyXrJfW55u/ZM9UAOkHT4VZebjJ1p6rTePhwB3pQbw41pU+LRAoGBAPoFLGhtm8OYor1XEWKDc8m+zmM5p3107EqUBDAa2lhBRv+Rs9opvzE5WfolQEIuELDErmDAIryc73oJVvprgvVQEDmTqBEeajTzOajoHmWDFkzlfLsh38V79RiTtvzBqqv7lSlPglWC4FIFv06/MOCEgLcKJC4CcD0REOJB8sVFAoGBANiVkW8SF7yAz3HC2UtrxYTiSXcwCHUW41PH35ceTGBcCezO1NjNnji+6fUXe8aO2ZkfcO+RtX+V+1/0uh6wivUydcBYaokEiuBWgmHhUA3guaXcntRlRX/+vNkfCTbeexU2wvWpOgjpZJIc0OOSphY5h081W0V68glkfKbTfJJ1AoGAdHZ0eTCVnkc7h3dHYdxZ02BOQ2pX2tjcOOE6Ei5wByxhJit42+cWaJeOkjdH94v9ulnj4K2fgLyZ0P8IxR3PovXomOlvIIpKbt/dvBBkGGU3vhooQANHeXaEdS5ZzuNn3TUUBmpDDBIq7ApBUy08V1lhm+HdeJEqN0f71Wli7QECgYBCSyXE9ajGTjcw/w88ArTmw8zw6obsMCFNF7TcsK14ITP4hCaqZeW0QNuj5ZhBYqBAOlt7OQJxFxtmd6YwlqEyBEv+oBGb9wP7Pv+RrkGuvcaVej4zjPwE/ZgYbWOIo/jQuX5ba30UFTxvbNqTjePAkcNU/2P8lHFZZqSw+7L5sQKBgDQC5LbKQwoR0IBa+AZ7GEkYdxBSkrfJL74FkYG7xspUK05I+3laHQxA4/vaQbBQ4p6JUroILUNgqPx+Ut4zaCQUDZa2oXp3F+LaLdZmGwxGVT7chOve6t+58ZAQ4ixSUYVtPrOoxmhaxGNGCcPBYT38V+x2Qh3uagdhlHZqLJqw",
		
		//异步通知地址
		'notify_url' => "http://www.laravel.com/notify_url",
		
		//同步跳转
		'return_url' => "http://www.laravel.com/returnPay",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwQAJO+LVXASCgD5navpiqPsqQWXXu42dpexH8bA9GADUr2k5e8utkskxGEHDFUxf6QUB8fC3jQ500kQi2Ys8AlpbhiwUM7RFVe7JQdj8gZl3GHvyfTKzl7rflIc2mWaoMLFmBwXCV8yT3s2ykisl7sYAr/KSYCArN7qqee8jMpgCoN01bf67O5sSF6XMEcDhdcFkbJRDoCX1rMG8JYjDwVg+SwvUepnHPfgLzk0c8tB/7w7wm8VP7zszcYD/zNvJceICpmeeLDuNFaE+hoZnu+HWr1JP5YI5p3kjwzyC46WAqIMl+milQR9N+FD09cPvMyJ6H1YaWug+8oy5T+gF+wIDAQAB",
]

?>