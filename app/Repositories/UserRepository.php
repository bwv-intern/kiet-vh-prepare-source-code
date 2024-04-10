<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public function getModel()
    {
        return User::class;
    }

    /**
     * Get user login
     *
     * @param array $params
     * @return mixed
     */
    public function getUserLogin(array $params)
    {
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
    public function search(array $params)
    {
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
            $date = Carbon::createFromFormat('d/m/Y', $params['date_of_birth'])->format('Y-m-d');
            $query->where('date_of_birth', $date);
        }

        if (isset($params['phone'])) {
            $query->where('phone', $params['phone']);
        }

        $query->orderBy('id', 'desc');
        return $query;
    }


    public function find($id): ?User {
        try {
            $user = User::find($id);
            return $user;
        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function  save($id = null, $params, $isFindAll = false){
        return parent::save($id,$params,$isFindAll);
    }

}
