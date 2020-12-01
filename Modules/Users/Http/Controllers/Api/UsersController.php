<?php

namespace Modules\Users\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Jobs\NotificationService;
use App\Notifications\ForgotPasswordMail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Mockery\Exception;
use Modules\Users\Entities\PasswordReset;
use Modules\Users\Entities\User;
use Modules\Users\Entities\UTCTime;
use Modules\Users\Http\Requests\RatingRequest;
use Modules\Users\Http\Requests\ResetPasswordRequest;
use Modules\Users\Http\Requests\UserSaveTokenRequest;
use Modules\Users\Transformers\UserTransformer;
use Modules\Users\Http\Requests\UserStoreRequest;
use Modules\Users\Http\Requests\UserUpdateRequest;
use Modules\Users\Repositories\UserRepository;
use Prettus\Validator\Exceptions\ValidatorException;
use Modules\Users\Http\Requests\UserUpdateMe;
use Modules\Users\Http\Requests\UserChangePassword;
use Modules\Files\Helper\FileHelper;
use Modules\Users\Http\Requests\AvatarRequest;
use Modules\Users\Http\Requests\SwitchRequest;

/**
 * Class UsersController
 * @property UserRepository $repository
 * @package Modules\Users\Http\Controllers\Api
 */
class UsersController extends BaseApiController
{

    /**
     * @var UserRepository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * get uesr login
     *
     * @param Request $request
     * @return mixed
     */
    public function me(Request $request)
    {
        $user = auth('admin')->user();
        return $this->responseSuccess($this->transform($user, UserTransformer::class, $request));
    }

    /**
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     */
    public function show(Request $request, $user_id)
    {
        $user = $this->repository->find($user_id);
        return $this->responseSuccess($this->transform($user, UserTransformer::class, $request));
    }

    /**
     * get list user active
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $role_id = $request->get('role_id', null);
        $isPaginate = $request->get('paginate', 1);
        $course_id = $request->get('course_id', null);
        $query = $this->repository;
        if ($role_id) {
            $query = $query->whereHas('roles', function ($sql) use ($role_id) {
                $sql->where('role_id', $role_id);
            });
        }
        if ($course_id) {
            $query = $query->whereHas('courses', function ($sql) use ($course_id) {
                $sql->where('course_id', $course_id);
            });
        }
        $users = $isPaginate ? $query->paginate(intval($request->get('per_page'))) : $query->get();
        return $this->responseSuccess($this->transform($users, UserTransformer::class, $request));
    }

    /**
     * create user
     *
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $timezoneDefault = UTCTime::query()->where('value', 'Asia/Bangkok')->first();

            $userInsert = [
                'name' => $request->get('name', null),
                'email' => $request->get('email', null),
                'password' => Hash::make($request->get('password') ? $request->get('password') : "123qaz"),
                'time_id' => $request->get('time_id', @$timezoneDefault->id),
            ];
            if ($request->has('avatar')) {
                $avatar = $request->file('avatar');
                $result = FileHelper::uploadFile($avatar, 'Users');
                $userInsert['avatar'] = $result['path'];
            }
            $user = $this->repository->create($userInsert);
            $user->syncRoles([$request->get('role_id')]);
            if ($request->get('course_ids')) {
                $user->courses()->sync($request->get('course_ids'));
            }
            DB::commit();
            return $this->responseSuccess($this->transform($user, UserTransformer::class, $request));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->responseErrors(500);
        }
    }

    /**
     * update user
     *
     * @param UserUpdateRequest $request
     * @param $user_id
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function update(UserUpdateRequest $request, $user_id)
    {
        DB::beginTransaction();
        try {
            $updateValue = $request->only(['name', 'email', 'role_id', 'time_id']);

            $user = $this->repository->update($updateValue, $user_id);
            if ($user) {
                $user->syncRoles($request->get('role_id'));
                $user->courses()->sync($request->get('course_ids'));
                if ($request->get('password')) {
                    $user->password = Hash::make($request->get('password'));
                    $user->save();
                }
            }

            DB::commit();
            return $this->responseSuccess($this->transform($user, UserTransformer::class, $request));
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->responseErrors(400);
        }
    }

    /**
     * @param AvatarRequest $request
     * @return JsonResponse
     */
    public function updateAvatarMe(AvatarRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('admin')->user();
            if ($request->has('avatar')) {
                $avatar = $request->file('avatar');
                $result = FileHelper::uploadFile($avatar, 'Users');
                $user->avatar = $result['path'];
                $user->save();
            }
            DB::commit();
            return $this->responseSuccess($this->transform($user, UserTransformer::class, $request));
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->responseErrors(500);
        }
    }

    /**
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function updateAvatar(AvatarRequest $request, $user_id)
    {
        DB::beginTransaction();
        try {
            $updateValue = [];
            if ($request->has('avatar')) {
                $avatar = $request->file('avatar');
                $result = FileHelper::uploadFile($avatar, 'Users');
                $updateValue['avatar'] = $result['path'];
            }
            $user = $this->repository->update($updateValue, $user_id);
            DB::commit();
            return $this->responseSuccess($this->transform($user, UserTransformer::class, $request));
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->responseErrors(500);
        }
    }

    /**
     * delete user
     *
     * @param $user_id
     * @return JsonResponse
     */
    public function destroy($user_id)
    {
        DB::beginTransaction();
        try {
            $this->repository->delete($user_id);
            DB::commit();
            return $this->responseSuccess(null, 200, 'Xóa thành công');
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->responseErrors(500);
        }
    }

    /**
     * @param UserUpdateMe $request
     * @return JsonResponse|int
     * @throws ValidatorException
     */
    public function updateMe(UserUpdateMe $request)
    {
        DB::beginTransaction();
        try {
            $updateVal = [
                'name' => $request->get('name', null),
                'email' => $request->get('email', null),
                'time_id' => $request->get('time_id', null)
            ];
            $user = $this->repository->update($updateVal, auth('admin')->user()->id);
            $user->syncRoles([$request->get('role_id')]);
            DB::commit();
            return $this->responseSuccess($this->transform($user, UserTransformer::class, $request));
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->responseErrors(500);
        }
    }

    /**
     * @param UserChangePassword $request
     * @return JsonResponse
     */
    public function changePassword(UserChangePassword $request)
    {

        try {
            $user = auth('admin')->user();
            if ($user->password && !Hash::check($request->get('current_password'), $user->password)) {
                return $this->responseErrors(400, "Mật khẩu hiện tại không đúng.");
            }
            $user->password = Hash::make($request->get('new_password'));
            $user->save();
            //
            $token = auth('admin')->refresh();

            $response = [
                'token' => $token,
                'expires_in' => auth('admin')->factory()->getTTL() * 60,
                'user' => $this->transform($user, UserTransformer::class, $request)
            ];

            return $this->responseSuccess($response);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->responseErrors(500, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTokenDevice(UserSaveTokenRequest $request)
    {
        DB::beginTransaction();
        try {
            $agent = new Agent();
            $user = $this->repository->find($request->user_id);
            if ($user) {
                if ($agent->isMobile()) {
                    if ($agent->is('iPhone')) {
                        $user->token_device_ios = $request->fcm_token;
                    } else {
                        $user->token_device_android = $request->fcm_token;
                    }
                } else {
                    $user->token_device = $request->fcm_token;
                }
                $user->save();
            }
            DB::commit();
            return $this->responseSuccess([], 200, 'token device updated');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return $this->responseErrors(500, $e->getMessage());
        }
    }

    /**
     * @param SwitchRequest $request
     * @return JsonResponse
     */
    public function switchUser(SwitchRequest $request)
    {
        DB::beginTransaction();
        try {
            $idSwitch = $request->get('switch_id');
            $userSwitch = $this->repository->where([
                'id' => $idSwitch,
            ])->first();

            if (null === $userSwitch) {
                return $this->responseErrors(404);
            }
            $newToken = auth('admin')->setTTL(config('app.ttl_switch'))->tokenById($userSwitch->id);
            $userSwitch->save();
            $response = [
                'token' => $newToken,
                'userSwitch' => $this->transform($userSwitch, UserTransformer::class, $request)
            ];
            DB::commit();
            return $this->responseSuccess($response);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return $this->responseErrors(500, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $email = $request->get('email');
            $user = User::query()->where('email', $email)->first();
            if (!$user) {
                return $this->responseErrors(404, 'email not found');
            }
            $token = Str::random(16);
            PasswordReset::create([
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            $linkReset = config('app.url_reset_client') . '?token=' . $token;
            $user->notify(new ForgotPasswordMail($linkReset));
            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return $this->responseErrors(500, $e->getMessage());
        }
    }

    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $token = $request->get('token');
            $passwordNew = $request->get('new_password');
            $tokenData = PasswordReset::query()->where('token', $token)->first();
            if (!$tokenData) {
                return $this->responseErrors(400, 'token undefined');
            }
            if (Carbon::now()->diffInHours($tokenData->created_at) > 1) {
                return $this->responseErrors(400, 'token expired');
            }
            $email = $tokenData->email;
            $user = User::query()->where('email', $email)->first();
            if (!$user) {
                return $this->responseErrors(400, 'email not found');
            }
            $user->password = Hash::make($passwordNew);
            $user->save();
            DB::commit();
            return $this->responseSuccess(null, 200, 'reset password success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return $this->responseErrors(500, $e->getMessage());
        }
    }


}
