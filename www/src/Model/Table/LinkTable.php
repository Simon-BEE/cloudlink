<?php
namespace App\Model\Table;

use Core\Model\Table;

class LinkTable extends Table
{
    public function findLinkByWord($string)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE title LIKE '%{$string}%' OR tag LIKE '%{$string}%' OR description LIKE '%{$string}%'");
    }

    public function lastLink()
    {
        return $this->query("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 3");
    }
}
