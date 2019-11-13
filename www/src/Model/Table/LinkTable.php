<?php
namespace App\Model\Table;

use Core\Model\Table;

class LinkTable extends Table
{
    public function findLinkByWord($string, $id)
    {
    return $this->query("SELECT * FROM {$this->table} WHERE title LIKE '%{$string}%' OR tag LIKE '%{$string}%' OR description LIKE '%{$string}%' AND WHERE `user` = {$id}");
    }

    public function lastLink($id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE `user`= {$id} ORDER BY id DESC LIMIT 3");
    }
}
