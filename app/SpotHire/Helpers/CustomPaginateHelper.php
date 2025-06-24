<?php
namespace App\SpotHire\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class CustomPaginateHelper {
	/**
	 * return custom paginated data on array items
	 *
	 * @param array $items
	 * @param int $perPage
	 * @return paginated data
	 */
    public static function getPaginate($items,$perPage, $urlpath, $query, $pageNo)
    {
   //  	$pageStart = 1;
			// $currentPage = (isset($_GET['page']) ?$_GET['page']:$pageStart) -1;
			// $pagedData = array_slice($items, $currentPage * $perPage, $perPage);
			// $items = new LengthAwarePaginator(
			// 		      $pagedData, 
			// 		      count($items), 
			// 		      $perPage
			// 		    );
			
			// $items->setPath($urlpath);

			// return $items;

			$pageStart = 1;
			$currentPage = (isset($pageNo) ? $pageNo : $pageStart) - 1;
			$pagedData = array_slice($items, $currentPage * $perPage, $perPage);
			$items = new LengthAwarePaginator(
					      $pagedData, 
					      count($items), 
					      $perPage,
					      \Illuminate\Pagination\Paginator::resolveCurrentPage(),
        				['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
					    );
			$items = $items->appends($query);
			
			return $items;
    }
}