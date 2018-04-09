<?php


namespace App\Library\Constant;


class StaticRoute
{
	const VUE_ROUTE = array (
		'admins' => '縣府員工',
		'company' => '營銷團隊',
		'users' => '臺東縣民',
		'changeMobile' => '手機轉碼',
		'changePass' => '更換密碼',
		'department' => '縣府單位',
		'department/group' => '縣民群組',
		'message/setting' => '推播設定',
		'message/list' => '推播訊息',
		'message/auto' => '自動推播訊息',
		'message/question' => '建立問卷',
		'message/activity' => '建立活動',
		'message/excel' => '產生報表',
		'gold/account' => '金幣賬戶',
		'gold/send' => '金幣發放',
		'goods/list' => '商品兌換',
		'oBick/voucher' => 'oBike優惠券',
		'gold/date' => '請款期別',
	);

	/**
	 * @return array
	 */
	public static function getAll() : array {
		return self::VUE_ROUTE;
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public static function getByName(string $name) : string {
		$arr = array_flip(self::VUE_ROUTE);
		return $arr[$name] ?? '';
	}

	/**
	 * @param string $path
	 * @return string
	 */
	public static function geyByPath(string $path) : string {
		$arr = self::VUE_ROUTE;
		return $arr[ltrim($path,'/')] ?? '';
	}

	/**
	 * @return array
	 */
	public static function getAllPath() :array {
		return array_keys(self::VUE_ROUTE);
	}

	/**
	 * @return array
	 */
	public static function getAllName() : array {
		return array_values(self::VUE_ROUTE);
	}

    /**
     * @param array $names
     * @return array
     */
	public static function getPathFromName(array $names) : array {
	    $router = [];
	    foreach ($names as $name) {
	        $has = array_search($name, self::VUE_ROUTE);
	        if($has !== false) {
	            $router[] = $has;
            }
        }
        return $router;
    }
}