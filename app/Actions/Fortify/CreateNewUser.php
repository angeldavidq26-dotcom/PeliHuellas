<?php

namespace App\Actions\Fortify;

use App\Actions\Teams\CreateTeam;
use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(private CreateTeam $createTeam)
    {
        //
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'telefono' => ['nullable', 'string', 'max:25'],
            'password' => $this->passwordRules(),
            'terms' => ['accepted'],
        ])->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name' => trim($input['nombres'].' '.$input['apellidos']),
                'nombres' => $input['nombres'],
                'apellidos' => $input['apellidos'],
                'email' => $input['email'],
                'telefono' => $input['telefono'] ?? null,
                'password' => $input['password'],
            ]);

            $this->createTeam->handle($user, $user->name."'s Team", isPersonal: true);

            return $user;
        });
    }
}
