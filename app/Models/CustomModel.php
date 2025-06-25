<?php
namespace App\Models;
use Awobaz\Compoships\Database\Eloquent\Model;
class CustomModel extends Model
{
    protected function getKeyForSaveQuery()
    {
        // If primary key is composite (array)
        if (is_array($this->primaryKey)) {
            $query = [];
            foreach ($this->primaryKey as $key) {
                // Use original value if available, otherwise current attribute
                $query[$key] = $this->original[$key] ?? $this->getAttribute($key);
            }
            return $query;
        }

        // Fallback to default behavior for single keys
        return $this->original[$this->getKeyName()] ?? $this->getKey();
    }
    protected function setKeysForSaveQuery($query)
    {
        if (is_array($this->getKeyName())) {
            foreach ($this->getKeyName() as $keyName) {
                $query->where($keyName, '=', $this->getKeyForSaveQuery()[$keyName]);
            }
            return $query;
        }

        return parent::setKeysForSaveQuery($query);
    }
}
