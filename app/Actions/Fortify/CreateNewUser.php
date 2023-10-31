<?php

namespace App\Actions\Fortify;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'cpf'                  => ['required', 'string', 'max:255'],
            'type'                 => ['required', 'string', 'in:' . implode(',', UserType::toArray())],
            'city'                 => ['required'],
            'address_street'       => ['required', 'string', 'max:255'],
            'address_number'       => ['required', 'string', 'max:255'],
            'address_neighborhood' => ['required', 'string', 'max:255'],
            'address_complement'   => ['required', 'string', 'max:255'],
            'password'             => $this->passwordRules(),
            'terms'                => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name'                 => $input['name'],
            'email'                => $input['email'],
            'cpf'                  => $input['cpf'],
            'type'                 => $input['type'],
            'cities_id'            => $input['city'],
            'address_street'       => $input['address_street'],
            'address_number'       => $input['address_number'],
            'address_neighborhood' => $input['address_neighborhood'],
            'address_complement'   => $input['address_complement'],
            'password'             => Hash::make($input['password']),
        ]);
    }
}
