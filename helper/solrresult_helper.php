<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Chen Ting
 *
 * personal helper for create facet link by using solr
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
 * Generates an HTML solr facet link.  
 * First param is the solr results array.
 * Second param is the facet field name in solr interface which we dont want to use again in our new query
 * 		so i unset it from original search array.
 * Third is URL which same as Codeigniter Anchor helper: controller/method
 * Last param is the original search array.
 * 
 * @access	public
 * @param	array
 * @param	string
 * @param	string
 * @param	array  
 * @return	string 
 */

if ( ! function_exists('crtFacet'))
{
	function crtFacet($resultArr, $searchField,$currentURL,$oriSearchKeyword)
	{	
		unset($oriSearchKeyword[$searchField]);
		$facetLink = '';
		$countArr = count($resultArr);
		$facetLink .= "<div class='facet'>";
		for ($i=0; $i < $countArr ; $i+=2) { 
				$facetLink .= "<div>";
				$facetLink .= "<a href='".base_url().'index.php'.''.$currentURL.'?'.$searchField.'='.$resultArr[$i];
				foreach ($oriSearchKeyword as $fieldkey => $fieldvalue) {
					$facetLink .= '&'.$fieldkey.'='.$fieldvalue;
				}
				$facetLink .= "&mysubmit=%E6%90%9C%E7%B4%A2%E6%9C%BA%E6%9E%84'>".$resultArr[$i]."&nbsp;(".$resultArr[$i+1].")</a>";
				$facetLink .= "</div>";
			}
			$facetLink .= "</div>";
			return $facetLink;

	}
}

// ------------------------------------------------------------------------
/**
 * Heading
 * Generates an json data for highcharts.js pie chart
 * 
 * 
 * @access	public
 * @param	array 		$resultArr: Facet array from solr interface
 * @param	int 		$int: more then n index combine array value into other
 * @return	string 		return json	
 * example: how to use this FacetForPieChart()
 *		js code
 *		series: [{
 *                 type: 'pie',
 *                 name: 'Browser share',
 *                 data: <?php print_r(FacetForPieChart($countryFacet,15)); ?>
 *      }]
 *
 */

if ( ! function_exists('FacetForPieChart'))
{
	function FacetForPieChart($resultArr,$num)
	{	
		$resultArrCount = count($resultArr);
		$FacetData = array();
		for ($i=0; $i < $resultArrCount ; $i+=2) { 
		    $FacetData[$resultArr[$i]] =$resultArr[$i+1];
		}

		if (count($FacetData) > $num) {
		    $dataDrwn  = array_slice($FacetData,0,$num-1);
		    $otherCountry = array_slice($FacetData,$num+1,count($FacetData));
		    $dataDrwn['others'] =  array_sum($otherCountry);
		} else {
		    $dataDrwn = $FacetData;
		}

		$last_key = end(array_keys($dataDrwn));
		$realdata = "[";
		foreach ($dataDrwn as $key => $value) {
		    if ($key != $last_key) {
		        $realdata .= "['".$key."',".$value."],";
		    } else {
		       $realdata .= "['".$key."',".$value."]";
		    }
		}
		$realdata .= "]";
		return $realdata;
	}
}

// ------------------------------------------------------------------------
/**
 * Heading
 *
 * 
 * @access	public
 * @param	array 		$resultArr: Facet array from solr interface
 * @param	int 		$int: more then n index combine array value into other
 * @return	string 		return array(title,data) for BarChart
 * 	
 */

if ( ! function_exists('FacetForBasicBarChart'))
{
	function FacetForBasicBarChart($resultArr)
	{	
		// print_r($resultArr);
		$resultArrCount = count($resultArr);
		$FacetData = array();
		for ($i=0; $i < $resultArrCount ; $i+=2) { 
		    $FacetData[$resultArr[$i]] =$resultArr[$i+1];
		}

		//each categories title write into a array-$FacetDataTile
		$FacetDataTitle = "[";
		$last_key = end(array_keys($FacetData));
		foreach ($FacetData as $key => $value) {
			if ($key != $last_key) {
		        $FacetDataTitle .= "'".$key."',";
		    } else {
		       $FacetDataTitle .= "'".$key."'";
		    }		
		}
		$FacetDataTitle .= "]";

		// Facet Data write into a array-$FacetDataForBar
		$FacetDataForBar = "[";
		$indx=0;
		foreach ($FacetData as $key => $value) {
			if ($key != $last_key) {
		        $FacetDataForBar .= "{ y:".$value.", color:colors[$indx]},";
		    } else {
		       $FacetDataForBar .= "{ y:".$value.", color:colors[$indx]}";
		    }	
		    $indx++;	
		}
		$FacetDataForBar .= "];";

		return array('title' => $FacetDataTitle, 'data' => $FacetDataForBar);
	}
}

?>