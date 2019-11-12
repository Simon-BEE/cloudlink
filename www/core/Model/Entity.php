<?php
namespace Core\Model;

class Entity
{
    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $methode = 'set'.ucfirst($key);
            if (method_exists($this, $methode)) {
                $this->$methode(htmlspecialchars($value));
            }
        }
    }

    public function objectToArray ($object) {
        $test = \get_class_methods($object);
        $i = 0;
        foreach($test as $value) {
            if (strpos($test[$i], 'get') !== false) {
                $key =  strpos($test[$i], 'get') + 3;
                $key = substr($test[$i], $key, strlen($test[$i]));
                $array[lcfirst($key)] = $object->$value();
                //echo $object->$value()."<br>";
            }
            $i++;
        }
        return $array;
    }
}
