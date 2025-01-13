<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $fillable = ["username", "password", "wins", "loses", "digievolutions"];

    public function insertUser(array $user): ?int
    {
        try {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
            $createdUser = self::create($user);
            return $createdUser->id;
        } catch (Exception $e) {
            echo "Exception captured: ", $e->getMessage(), "<br>";
            return null;
        }
    }

    public function updateUser(array $arrayData):bool {
        try {
            //Busco al usuario
            $user = User::findOrFail($arrayData["id"]);

            if ($arrayData["password"] != $arrayData["originalPassword"]) {
                $arrayData["password"] = password_hash($arrayData["password"], PASSWORD_DEFAULT);
            }

            $user->update([
                "username" => $arrayData["username"],
                "password" => $arrayData["password"],
                "wins", $arrayData["wins"],
                "loses", $arrayData["loses"],
                "digievolutions", $arrayData["digievolutions"]
            ]);

            return true;
        
        } catch (Exception $e) {
            echo "Exception captured: ", $e->getMessage(), "<br>";
            return false;
        }
    }

    public function deleteUser(array $arrayData): bool {
        try {
            $user = User::find($arrayData["id"]);
            $user->delete();
            return true;
        } catch (Exception $e) {
            echo "Exception captured: ", $e->getMessage(), "<br>";
            return false;
        }
    }
}