<?php

namespace App\Http\Controllers;

use App\User;
use Faker\Generator;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseController extends Controller
{
    protected $database;
    protected $faker;

    public function __construct(Generator $faker) {
        $serviceAccount = ServiceAccount::fromJsonFile(storage_path('FirebaseKey.json'));

        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://laravel-firebase-523b6.firebaseio.com/')
            ->create();

        $this->database = $firebase->getDatabase();
        $this->faker = $faker;
    }
    public function setposts()
    {
        $createPost = $this->database
            ->getReference('/posts')
            ->push([
                'title' => $this->faker->sentence,
                'body' => $this->faker->paragraph,
            ]);

        return response()->json(['post' => $createPost->getValue()]);
    }

    public function getposts()
    {
        $posts = $this->database->getReference('/posts')->getValue();

        return response()->json(['posts' => $posts]);
    }

    public function setusers()
    {
        $user = User::first()->toArray();

        $createUser = $this->database->getReference('/users')->push($user);

        return response()->json(['user' => $createUser->getValue()]);
    }

    public function getusers()
    {
        $users = $this->database->getReference('/users')->getValue();

        return response()->json(['users' => $users]);
    }
}
