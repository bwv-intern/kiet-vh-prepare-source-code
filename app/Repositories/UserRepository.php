<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public function getModel() {
        return User::class;
    }

    /**
     * Get user login
     *
     * @param array $params
     * @return mixed
     */
    public function getUserLogin(array $params) {
        $query = User::query()
            ->where('email', $params['email'] ?? null)
            ->where('del_flg', $this->validDelFlg);
        $user = $query->get()->first();
        if ($user && Hash::check($params['password'], $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Search user
     *
     * @param array $params
     * @return mixed
     */
    public function search(array $params) {
        $query = User::whereRaw('1=1');
        $query->where('del_flg', '=', $this->validDelFlg);

        if (isset($params['email'])) {
            $query->where('email', $params['email']);
        }

        if (isset($params['name'])) {
            $query->where('name', 'LIKE', '%' . $params['name'] . '%');
        }

        if (isset($params['user_flg'])) {
            $query->whereIn('user_flg', $params['user_flg']);
        }

        if (isset($params['date_of_birth'])) {
            $date = Carbon::createFromFormat('Y/m/d', $params['date_of_birth'])->format('Y-m-d');
            $query->where('date_of_birth', $date);
        }

        if (isset($params['phone'])) {
            $query->where('phone', $params['phone']);
        }

        $query->orderBy('id', 'desc');

        return $query;
    }

    public function exportCSV(array $params) {
        $query = User::whereRaw('1=1');
        $query->where('del_flg', '=', $this->validDelFlg);

        if (isset($params['email'])) {
            $query->where('email', $params['email']);
        }

        if (isset($params['name'])) {
            $query->where('name', 'LIKE', '%' . $params['name'] . '%');
        }

        if (isset($params['user_flg'])) {
            $query->whereIn('user_flg', $params['user_flg']);
        }

        if (isset($params['date_of_birth'])) {
            $date = Carbon::createFromFormat('Y/m/d', $params['date_of_birth'])->format('Y-m-d');
            $query->where('date_of_birth', $date);
        }

        if (isset($params['phone'])) {
            $query->where('phone', $params['phone']);
        }

        $query->orderBy('id', 'desc');
        $results = [];

        $query->chunk(100, function ($users) use (&$results) {
            foreach ($users as $user) {
                $date_of_birth = $user->date_of_birth ? $user->date_of_birth->format('d/m/Y') : '';
                $created_at = $user->created_at ? $user->created_at->format('d/m/Y') : '';
                $results[] = array_map(function ($value) {
                    return '"' . $value . '"';
                }, [$user->id,
                    $user->email,
                    $user->name,
                    $user->user_flg,
                    $date_of_birth,
                    $user->phone,
                    $user->address,
                    $user->del_flg,
                    $user->created_by,
                    $created_at,
                ]);
            }
        });

        return $results;
    }

    public function find($id): ?User {
        return parent::findById($id, false);
    }

    public function save($id = null, $params, $isFindAll = false) {
        return parent::save($id, $params, $isFindAll);
    }

    public function findByEmail($email) {
        try {
            $user = User::where('email', $email)->first();

            return $user;
        } catch (Exception $e) {
            return null;
        }
    }

    public function editMany($users) {
        DB::beginTransaction();
        try {
            foreach ($users as $user) {
                $id = $user['id'];
                unset($user['id']);
                DB::table('users')->where('id', $id)->update($user);
            }
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function insertMany($arrData) {
        DB::beginTransaction();
        try {
            DB::table('users')->insert($arrData);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
