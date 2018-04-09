<?php
namespace App\Library;

use App\Exceptions\ErrorConstant;

class ArrayParse
{
	/**
	 * 從一個數組內copy出想要的key
	 * @param array $keys
	 * @param array $sourceArray
	 * @return array
	 */
	public static function arrayCopy(array $keys, array $sourceArray) : array
	{
		$arr = [];
		foreach ($keys as $key) {
			if(isset($sourceArray[$key])) {
				$arr[$key] = $sourceArray[$key];
			}
		}
		return $arr;
	}

	/**
	 * 對比參數
	 * @param array $params
	 * @param array $diffArray
	 * @return array
	 * @throws \Exception
	 */
	public static function checkParamsArray(array $params, array $diffArray = []) : array
	{
		if(empty($diffArray)) {
			$diffArray = $_POST;
		}
		$diff = array_diff($params, array_keys($diffArray));
		if(count($diff) > 0) {
			throw new \Exception('params lost '.implode(',',$diff), ErrorConstant::PARAMS_LOST);
		}
		$response = [];
		foreach ($params as $param) {
		        if($diffArray[$param] === null) {
		                $diffArray[$param] = '';
                        }
                        $response[$param] = $diffArray[$param];
		}
		if(isset($response['mobile'])) {
		        $response['mobile'] = ltrim($response['mobile'], '0 ');
                }
		return $response;
	}

	/**
	 * 移除所有包含時間的字段
	 * @param array $params
         * @param $timeField
	 * @return array
	 */
	public static function diffTime(array $params, string $timeField = '_time') : array
	{
		$arr = [];
		foreach ($params as $key=>$value) {
			if(strpos($key, $timeField)===false) {
				$arr[$key] = $value;
			}
		}
		return $arr;
	}

	/**
	 * 获取所有时间字段
	 * @param array $params
	 * @param string $timeField
	 * @return array
	 */
	public static function getTime(array $params, string $timeField = '_time') : array
	{
		$arr = [];
		foreach ($params as $key=>$value) {
			if(strpos($key, $timeField)!==false) {
				$arr[$key] = $value;
			}
		}
		return $arr;
	}

	/**
	 * 二維數組根據key進行排序
	 * @param array $array
	 * @param string $key
	 * @param string $order
	 */
	public static function multiSortArray(array &$array, string $key, $order = 'desc')
	{
		usort($array, function($one, $two)use($key, $order) {
			if($one[$key] > $two) {
				return $order == 'desc' ? -1 : 1;
			}
			return $order == 'desc' ? 1 : -1;
		});
	}

	/**
	 * 字符串化
	 * @param array $array
	 * @return array
	 */
	public static function strval(array $array, array $except = [])
	{
		return StrParse::strval($array, $except);
	}

	/**
	 * 将普通数组变成mongo的数组
	 * @param array $array
	 * @param string $property
	 * @return array
	 */
	public static function toMongoData(array $array, string $property) : array
	{
		$result = [];
		if(isset($array['lat']) and isset($array['lng'])) {
			$result['location'] = [
				'type'  =>      'Point',
				'corrdinates'   =>      [
					$array['lng'],
					$array['lat']
				]
			];
			unset($array['lat']);
			unset($array['lng']);
		}
		foreach ($array as $key=>$value) {
			if (is_array($value)) {
				$result = array_merge($result,self::toMongoData($value, $property.'.'.$key));
			} else {
				$result[$property.'.'.$key] = $value;
			}
		}
		return $result;
	}


        /**
         * 覆蓋合並並且替換
         * @param array $arr1
         * @param array $arr2
         * @return array
         */
	public static function arrayMergeCover(array $arr1, array $arr2) : array
        {
                $diffKey = array_diff_key($arr2, $arr1);
                if (!empty($diffKey)) {
                        foreach ($diffKey as $key) {
                                $arr1[$key] = $arr2[$key];
                                unset($arr2[$key]);
                        }
                }

                foreach ($arr1 as $key => $value) {
                        if (is_array($value)) {
                                $arr1[$key] = self::arrayMergeCover($arr1[$key], $arr2[$key]);
                        } else {
                                $arr1[$key] = isset($arr2[$key]) ? $arr2[$key] : $arr1[$key];
                        }
                }

                return $arr1;
        }

        /**
         * 合並並且移除空的字符下標
         * @param array $arr1
         * @param array $arr2
         * @return array
         */
        public static function arrayMergeRemove(array $arr1, array $arr2) : array
        {
                $diffKey = array_diff_key($arr1, $arr2);
                if(!empty($diffKey)) {
                        throw new \InvalidArgumentException('參數個數錯誤:'.json_encode($diffKey),ErrorConstant::SYSTEM_ERR);
                }

                foreach ($arr1 as $key=>$value) {
                        if(is_array($value)) {
                                $arr1[$key] = self::arrayMergeRemove($arr1[$key], $arr2[$key]);
                        }else{
                                if($arr2[$key] !== '') {
                                        $arr1[$key] = $arr2[$key];
                                }else{
                                        unset($arr1[$key]);
                                }
                        }
                }

                return $arr1;
        }
}