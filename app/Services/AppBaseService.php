<?php

namespace App\Services;


use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;


class AppBaseService
{

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var null | mixed
     */
    protected $data;
    /**
     * @var string
     */
    protected string $message;
    /**
     * @var int
     */
    protected int $code;
    /**
     * @var bool
     */
    protected bool $status;

    /**
     * @var mixed | BaseRepository
     */
    protected mixed $repository;


    /**
     * @param string $repository
     * @param Request|null $request
     */
    public function __construct(string $repository, ?Request $request = null)
    {
        $this->repository = app($repository);

        if ($request) {
            $this->request = $request;
        }
    }

    /**
     * @param mixed $data
     * @param int|null $code
     * @param bool|null $status
     * @return AppBaseService
     */
    public function success(mixed $data, ?int $code = null, ?bool $status = null): object
    {
        $this->data = $data;
        $this->code = $code ?? 200;
        $this->status = $status ?? true;

        return $this;
    }


    /**
     * @param string $message
     * @param int|null $code
     * @return AppBaseService
     */
    public function failed(string $message, ?int $code = null): object
    {
        $this->data = null;
        $this->message = $message;
        $this->code = $code ?? 422;
        $this->status = false;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }
}
