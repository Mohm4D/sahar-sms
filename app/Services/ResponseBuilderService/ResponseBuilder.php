<?php

namespace App\Services\ResponseBuilderService;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;


class ResponseBuilder
{

    /**
     * @param $result
     * @param $message
     * @param int $statusCode
     * @return mixed
     */
    public function success($data=[],int $statusCode = 200): mixed
    {
        if (is_object($data) && property_exists($data, "resource") && $data->resource instanceof LengthAwarePaginator) {
            $data = $data->resource->toArray();
            $result["results"] = $data['data'];
            $result["pagination"] = Arr::except($data, ['data', 'links']);
            $data = $result;
        }
        return Response::json(
            [
                'success' => true,
                'data' => $data,
                'message' => 'پاسخ به درخواست شما موفقیت آمیز بود',
            ]
            , $statusCode);
    }

    /**
     * @param int $statusCode
     * @param array $data
     * @return mixed
     */
    public function error( $data = [],int $statusCode = 500): mixed
    {
        $message = match ($statusCode) {
            401 => 'شرمنده، احراز هویت شما انجام نشد! لطفا مجددا وارد سیستم شوید',
            403 => 'درخواست غیرمجاز',
            404 => 'یافت نشد',
            405 => 'متد درخواستی مجاز نیست',
            422 => 'فرمت داده های ارسالی قابل قبول نیست',
            500 => 'شرمنده، خطایی در سیستم رخ داده!',
            default => 'شرمنده، خطای نامشخصی رخ داده!',
        };
        return Response::json([
            'success' => false,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

}
