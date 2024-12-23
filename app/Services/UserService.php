<?php

namespace App\Services;

use App\Criteria\AddEagerLoadingCriteria;
use App\Criteria\ByIdCriteria;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * @property string $mobile
 * @property string $password
 */
class UserService extends AppBaseService
{
    private $relations = ['roles.permissions'];

    /**
     * @return UserService
     */
    public function all(): UserService
    {
        try {
            $this->repository->pushCriteria(new AddEagerLoadingCriteria($this->relations));

            $data = $this->repository->groupBy('id')->paginate($this->request['perPage'] ?? 15);
            return $this->success($data, 200);

        } catch (Exception $exception) {
            return $this->failed($exception->getMessage(), 500);
        }
    }

    /**
     * @return UserService
     */
    public function store(): UserService
    {
        try {
            return $this->success(
                $this->repository->create([
                    'mobile'   => $this->request->input('mobile'),
                    'password' => null
                ]),
                201,
            );

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->failed(
                $exception->getMessage(),
                500,
            );
        }
    }

    /**
     * @return UserService
     */
    public function byId(): UserService
    {
        try {
            $id = $this->request->input('id');
            $this->repository->pushCriteria(new ByIdCriteria($id));
            /** @var User $user */
            if (!$user = $this->repository->first()) {
                return $this->failed(trans('validation.user.notfound'));
            }

            return $this->success($user->load($this->relations));

        } catch (Exception $exception) {
            Log::error('UserService: Error');
            Log::error($exception->getMessage());

            return $this->failed($exception->getMessage(), 500);
        }
    }

    /**
     * @return UserService
     */
    public function update(): UserService
    {
        try {
            $this->repository->pushCriteria(new ByIdCriteria($this->request->input('id')));
            if (!$this->repository->first()) {
                return $this->failed(trans('validation.user.notfound'));
            }

            $values = $this->request->only(['active', 'status']);
            $this->repository->popCriteria(ByIdCriteria::class);
            /** @var User $user */
            $user = $this->repository->update($values, $this->request->input('id'));

            return $this->success($user->load($this->relations));
        } catch (Exception $exception) {
            Log::error('UserService: Error');
            Log::error($exception->getMessage());
            return $this->failed($exception->getMessage(), 500);
        }
    }

}
