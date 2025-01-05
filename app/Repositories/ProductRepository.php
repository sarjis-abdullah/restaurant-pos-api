<?php

namespace App\Repositories;

use App\Repositories\Contracts\ProductInterface;
use Carbon\Carbon;

class ProductRepository extends BaseRepository implements ProductInterface
{
    /**
     * @inheritdoc
     */
    public function findBy(array $searchCriteria = [], $withTrashed = false, $onlyTrashed = false)
    {
        $queryBuilder = $this->model;

        if (isset($searchCriteria['query'])) {
            $searchCriteria['id'] = $queryBuilder->where('name', 'like', '%' . $searchCriteria['query'] . '%')
                ->orWhere('barcode', 'like', $searchCriteria['query'] . '%')
                ->pluck('id')->toArray();

            if (isset($searchCriteria['id'])) {
                $searchCriteria['id'] = is_array($searchCriteria['id']) ? implode(",", array_unique($searchCriteria['id'])) : $searchCriteria['id'];
            }

            unset($searchCriteria['query']);

        }

        if (isset($searchCriteria['endDate'])) {
            $queryBuilder = $queryBuilder->whereDate('created_at', '<=', Carbon::parse($searchCriteria['endDate']));
            unset($searchCriteria['endDate']);
        }

        if (isset($searchCriteria['startDate'])) {
            $queryBuilder = $queryBuilder->whereDate('created_at', '>=', Carbon::parse($searchCriteria['startDate']));
            unset($searchCriteria['startDate']);
        }

        if (isset($searchCriteria['categoryIds'])){
            $multiCategories = explode(',', $searchCriteria['categoryIds']);
            $queryBuilder = $queryBuilder->whereIn('categoryId', $multiCategories);
            unset($searchCriteria['categoryIds']);
        }


        $searchCriteria = $this->applyFilterInProductSearch($searchCriteria, $onlyTrashed);

        $queryBuilder = $queryBuilder->where(function ($query) use ($searchCriteria) {
            $this->applySearchCriteriaInQueryBuilder($query, $searchCriteria);
        });

        $limit = !empty($searchCriteria['per_page']) ? (int)$searchCriteria['per_page'] : 15;
        $orderBy = !empty($searchCriteria['order_by']) ? $searchCriteria['order_by'] : 'id';
        $orderDirection = !empty($searchCriteria['order_direction']) ? $searchCriteria['order_direction'] : 'desc';

        if ($orderBy == 'quantity') {
            $queryBuilder = $queryBuilder->withCount(['stocks as totalStockQuantity' => function ($query) {
                $query->select(DB::raw('COALESCE(sum(quantity),0) as totalStockQuantity'));
            }]);

            $queryBuilder = $queryBuilder->orderBy('totalStockQuantity', $orderDirection);
        } else {
            $queryBuilder->orderBy($orderBy, $orderDirection);
        }

        if ($withTrashed) {
            $queryBuilder->withTrashed();
        }
        if ($onlyTrashed) {
            $queryBuilder->onlyTrashed();
        }




        if (empty($searchCriteria['withoutPagination'])) {
            $page = !empty($searchCriteria['page']) ? (int)$searchCriteria['page'] : 1;
            $products = $queryBuilder->paginate($limit, ['*'], 'page', $page);
        } else {
            $products = $queryBuilder->get();
        }

        return $products;
//        return ['products' => $products, 'pageWiseSummary' => $pageWiseSummary];
    }

    /**
     * shorten the search based on search criteria
     *
     * @param array $searchCriteria
     * @return mixed
     */
    public function applyFilterInProductSearch(array $searchCriteria = [], bool $onlyTrashed = false)
    {
        if (isset($searchCriteria['query'])) {
            $model = $onlyTrashed ? $this->model->onlyTrashed() : $this->model;
            $searchCriteria['id'] = $model->where('name', 'like', '%' . $searchCriteria['query'] . '%')
                ->orWhere('barcode', 'like', $searchCriteria['query'] . '%')
                ->pluck('id')->toArray();
            unset($searchCriteria['query']);
        }

//        if (isset($searchCriteria['sku'])) {
//            $stockRepository = app(StockRepository::class);
//            $stockRepositoryModel = $onlyTrashed ? $stockRepository->model->onlyTrashed() : $stockRepository->model;
//            $productIds = $stockRepositoryModel->where('sku', $searchCriteria['sku'])->pluck('productId')->toArray();
//
//            $searchCriteria['id'] = isset($searchCriteria['id']) ? array_intersect($searchCriteria['id'], $productIds) : $productIds;
//
//            unset($searchCriteria['sku']);
//        }

        if (isset($searchCriteria['id'])) {
            $searchCriteria['id'] = is_array($searchCriteria['id']) ? implode(",", array_unique($searchCriteria['id'])) : $searchCriteria['id'];
        }

        return $searchCriteria;
    }
}
