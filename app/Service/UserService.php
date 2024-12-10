<?php

namespace JackBerck\Ambatuflexing\Service;

use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Domain\User;
use JackBerck\Ambatuflexing\Exception\ValidationException;
use JackBerck\Ambatuflexing\Model\AdminManageUsersRequest;
use JackBerck\Ambatuflexing\Model\AdminManageUsersResponse;
use JackBerck\Ambatuflexing\Model\UserLoginRequest;
use JackBerck\Ambatuflexing\Model\UserLoginResponse;
use JackBerck\Ambatuflexing\Model\UserPasswordUpdateRequest;
use JackBerck\Ambatuflexing\Model\UserPasswordUpdateResponse;
use JackBerck\Ambatuflexing\Model\UserProfileUpdateRequest;
use JackBerck\Ambatuflexing\Model\UserProfileUpdateResponse;
use JackBerck\Ambatuflexing\Model\UserRegisterRequest;
use JackBerck\Ambatuflexing\Model\UserRegisterResponse;
use JackBerck\Ambatuflexing\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws ValidationException
     */
    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findByField('email', $request->email);
            if ($user != null) {
                throw new ValidationException("User Id already exists");
            }

            $user = new User();
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->user = $user;

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserRegistrationRequest(UserRegisterRequest $request): void
    {
        if (
            $request->email == null || $request->username == null || $request->password == null ||
            trim($request->email) == "" || trim($request->username) == "" || trim($request->password) == ""
        ) {
            throw new ValidationException("Id, Name, Password can not blank");
        }
    }

    /**
     * @throws ValidationException
     */
    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findByField('email', $request->email);
        if ($user == null) {
            throw new ValidationException("Email or Password is wrong");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("Email or Password is wrong");
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserLoginRequest(UserLoginRequest $request): void
    {
        if (
            $request->email == null || $request->password == null ||
            trim($request->email) == "" || trim($request->password) == ""
        ) {
            throw new ValidationException("Email and Password can not blank");
        }
    }

    /**
     * @throws ValidationException
     */
    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
    {
        $this->validateUserProfileUpdateRequest($request);
        $pathFile = __DIR__ . "/../../public/images/profiles/";

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField('id', $request->id);
            if ($user == null) {
                throw new ValidationException("User is not found");
            }

            $user->username = $request->username;
            $user->position = $request->position;
            $user->bio = $request->bio;

            if ($user->photo != null && $request->photo != null) {
                unlink($pathFile . $user->photo);
            }

            if ($request->photo && isset($request->photo["tmp_name"])) {
                $extension = pathinfo($request->photo["name"], PATHINFO_EXTENSION);
                $photoName = uniqid() . "." . $extension;

                move_uploaded_file($request->photo["tmp_name"], $pathFile . $photoName);

                $user->photo = $photoName;
            }

            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserProfileUpdateResponse();
            $response->user = $user;
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserProfileUpdateRequest(UserProfileUpdateRequest $request): void
    {
        if (
            $request->id == null || $request->username == null ||
            trim($request->id) == "" || trim($request->username) == ""
        ) {
            throw new ValidationException("Id, username can not blank");
        }

        if (isset($request->photo)) {
            if ($request->photo == null && $request->photo["tmp_name"] == "") {
                throw new ValidationException ("image cannot be empty");
            }

            if ($request->photo["error"] != UPLOAD_ERR_OK) {
                throw new ValidationException ("image error");
            }

            if (!in_array($request->photo["type"], ['image/jpeg', 'image/png', 'image/jpg'])) {
                throw new ValidationException ("image type is not allowed");
            }

            if ($request->photo["size"] > 2 * 1024 * 1024) {
                throw new ValidationException ("image size is too large");
            }
        }
    }

    /**
     * @throws ValidationException
     */
    public function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse
    {
        $this->validateUserPasswordUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField('id', $request->id);
            if ($user == null) {
                throw new ValidationException("User is not found");
            }

            if (!password_verify($request->oldPassword, $user->password)) {
                throw new ValidationException("Old password is wrong");
            }

            $user->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserPasswordUpdateResponse();
            $response->user = $user;
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserPasswordUpdateRequest(UserPasswordUpdateRequest $request): void
    {
        if (
            $request->id == null || $request->oldPassword == null || $request->newPassword == null ||
            trim($request->id) == "" || trim($request->oldPassword) == "" || trim($request->newPassword) == ""
        ) {
            throw new ValidationException("Old Password and New Password can not blank");
        }
    }

    public function manage(AdminManageUsersRequest $request): AdminManageUsersResponse
    {
        $data = $this->userRepository->search($request->email, $request->username, $request->position, $request->page, $request->limit);
        $res = new AdminManageUsersResponse();
        $res->users = $data['users'];
        $res->totalUsers = $data['total'];
        return $res;
    }
}
