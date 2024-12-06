<?php

namespace App\Models;

use Statamic\Entries\Entry;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Hash;
use Statamic\Facades\Entry as EntryAPI;

class Member extends Entry implements AuthenticatableContract
{
    use Authenticatable;

    protected $collection = 'members';

    public function getKeyName()
    {
        return 'id';
    }

    public function getKey()
    {
        return $this->id();
    }

    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->get('password');
    }

    public function setPassword($password)
    {
        $this->set('password', Hash::make($password));
        return $this;
    }

    public static function findByEmail($email)
    {
        $entry = EntryAPI::query()
            ->where('collection', 'members')
            ->where('data->email', $email)
            ->first();

        return $entry ? static::fromEntry($entry) : null;
    }

    public static function fromEntry($entry)
    {
        return (new static)
            ->collection($entry->collection())
            ->blueprint($entry->blueprint())
            ->locale($entry->locale())
            ->data($entry->data())
            ->id($entry->id());
    }
}
