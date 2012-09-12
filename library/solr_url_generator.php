<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Chen Ting
 *
 * personal helper for create solr query url
 *
 * @author		Chen Ting
 * @copyright	free to use
 * @link		
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------
/**
 * Heading
 *
 * 自动生成solr url
 * 参数1：solr基础路径
 * 参数2：传入搜索项array 
 * 参数3：关系参数eg-AND/OR
 * 参数4和5：其实记录号、perpage号
 * 参数6：返回格式eg-json/xml
 * 参数7：传入分面项array
 * @access	public
 * @param	string
 * @param	array
 * @param	string
 * @param	int
 * @param	int
 * @param	string
 * @param	array
 * @return	 
 */



class Solr_url_generator
{
	public function getInsUrl($baseurl,$field,$operator,$offset,$limits,$format,$facet)
	{
		
		$fieldFiltered = array_filter($field);
		$last_key = end(array_keys($fieldFiltered));
		// print_r($fieldFiltered);
		// 开始拼solr url
		$insUrl = $baseurl;
		$insUrl .= '?q=';
		// 循环每个字段，如果多字段中间用$operator连接
		foreach ($fieldFiltered as $key => $value) {
			$arr=preg_split('/\s+/',trim($value));
			$last_innerkey = end(array_keys($arr));
			if ($key != $last_key) {
				if (isset($value)) {
					$insUrl .= $key.':'.urlencode($value).'%20'.$operator.'%20';
				} else {
					 $insUrl .='';
				}
				
			} else {
				//最后只有一个字段的时候，用户输入多个空格分隔符，循环每个word
				if (count($arr) > 1 ){
					foreach ($arr as $innerkey => $innerValue) {
						
						if ($innerkey != $last_innerkey) {
							$insUrl .=$key.':'.urlencode($innerValue).'%20AND%20';
						} else {
							$insUrl .=$key.':'.urlencode($innerValue);
						}
					}
				} else {
					$insUrl .=  $key.':'.urlencode($value);
				}
			}
		}
		$insUrl .='&version=2.2&start='.$offset.'&rows='.$limits.'&indent=on&wt='.$format.'&facet=on';
		foreach ($facet as $key => $value) {
			$insUrl .= '&facet.field='.$value;
		}
		$insUrl .='&facet.mincount=1';
		return $insUrl;
	}
}