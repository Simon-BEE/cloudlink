<?php
namespace App\Model\Table;

use Core\Model\Table;

class UserTable extends Table
{
    public function verifyUser(string $nicknameOrMail, string $password)
    {
        $user = $this->query("SELECT id, password, token FROM user WHERE nickname = '$nicknameOrMail' OR mail = '$nicknameOrMail'", null, true);
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $user->setPassword('');
                return $user;
            }
        }
    }

    public function updateToken(int $id, string $token)
    {
        return $this->query("UPDATE user SET token = 'checked' WHERE id = :id AND token = '$token'", [':id' => $id]);
    }

    public function tempPassword(string $mail)
    {
        $password = substr(uniqid(), 6, 12);
        $pwdHashed = password_hash($password, PASSWORD_BCRYPT);
        $this->query("UPDATE user SET password = '$pwdHashed' WHERE mail = ?", [$mail]);
        return $password;
    }

    public function getUserDatasWithoutPwd(int $id)
    {
        return $this->query("SELECT id, role_id, nickname, firstname, lastname, mail, website FROM user WHERE id = $id", null, true);
    }
    
}
