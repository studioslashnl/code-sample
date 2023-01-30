<?php
// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose.

namespace App\Http\Controllers\API;

use App;
use App\User;
use App\School;
use App\Student;
use App\Teacher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\EntreeLoginRequest;
use Carbon\Carbon;

class EntreeAuthController extends Controller
{
    /**
     * Perform a login request, return tokens to Entree
     *
     * @param EntreeLoginRequest $request
     */
    public function login(EntreeLoginRequest $request)
    {
        // We can only accept requests coming from our own server
        // NB: This is explicitly hardcoded
        if (!App::environment('local') && $request->ip() !== '3.127.243.181') {
            return response()->json([
                'error' => 'Forbidden.',
                'details' => 'Tried to access from ' . $request->ip()
            ], 401);
        }

        // Check for BRIN code
        // if we don't know it: show an error for students
        // still login teachers but send a notification to CS
        $school = School::where('brin', '=', $request->get('brin'))->first();

        if (!$school) {
            return response()->json([
                'error' => 'Jouw school is (nog) niet aangemeld voor CodeSkillz.',
            ], 401);
        }

        // Check if we know the email but without the entree ID
        // In that case, we should show an error
        $check = User::where('entree_uid', '!=', $request->get('entree_uid'))
            ->where('email', '=', $request->get('email'))->count();

        if ($check) {
            return response()->json([
                'error' => 'Het lijkt er op dat je al een account had, maar deze kon niet aan je schoolaccount gekoppeld worden. Neem contact op om het probleem te verhelpen.',
            ], 401);
        }

        // If we don't know the user, the request should be complete
        $userExists = User::where('entree_uid', '=', $request->get('entree_uid'))->count() > 0;

        $SchoolHasClasses = $school->classes()->count() > 0;

        if (!$userExists && $request->isSimple($SchoolHasClasses) !== true) {
            $missing = $request->isSimple($SchoolHasClasses);

            return response()->json([
                'error' => 'Deze velden zijn ook verplicht: ' . $missing->implode(', '),
            ], 401);
        }

        // Check if a user exists, otherwhise, create it
        $user = User::where('entree_uid', '=', $request->get('entree_uid'))
            ->firstOr(function () use ($request) {
                $user = User::create([
                    'entree_uid' => $request->get('entree_uid'),
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'email' => strtolower($request->get('email')),
                    'password' => Hash::make(Str::random(20)),
                    'kennisnet_info' => $request->get('kennisnet_info'),
                ]);

                if ($request->get('account_type') == 'teacher') {
                    // Ff the user is a teacher; create a teacher record
                    $teacher = Teacher::create(['super_teacher' => false]);
                    $user->userable()->associate($teacher);
                    $user->save();
                } elseif ($request->get('account_type') == 'student') {
                    // If the user is a student; save the academic level
                    // If the user is a student; create a student record
                    $student = Student::create([
                        'academic_level' => $request->get('academic_level')
                    ]);
                    $user->userable()->associate($student);
                    $user->save();
                }

                return $user;
            });

        if (!$user) {
            return response()->json([
                'error' => 'Je hebt geen account in ons systeem. Neem contact op om het probleem te verhelpen.',
            ], 404);
        }

        // If no entree uid has been saved, we've found the user on email
        // In that case, update our details with the Entree data
        if ($request->has('entree_uid')) {
            $user->entree_uid = $request->get('entree_uid');
        }

        if ($request->has('first_name')) {
            $user->first_name = $request->get('first_name');
        }

        if ($request->has('last_name')) {
            $user->last_name = $request->get('last_name');
        }

        if ($request->has('email')) {
            $user->email = $request->get('email');
        }

        $user->save();

        // If the user has no classes
        if ($user->userable->classes->count() == 0) {
            $studygroups_names = $request->get('study_group');
            if (!is_array($studygroups_names)) {
                $studygroups_names = [$request->get('study_group')];
            }

            // Add the user to the appropiate class, if it is a student
            $studygroups = $school->classes()
                ->whereIn('name', $studygroups_names)
                ->get();

            // If we did not find ANY classes for a student
            if (!$studygroups && $request->get('account_type') == 'student') {
                return response()->json([
                    'error' => 'Voor jouw account zijn er geen klassen gevonden in ons systeem.',
                ], 401);
            }

            // Add the classes to the user
            if ($studygroups->count() > 0 && $request->get('account_type') == 'teacher') {
                $studygroups->each(function ($studygroup) use ($user) {
                    $studygroup->teachers()->attach($user->userable);
                    $studygroup->save();
                });
            } elseif ($studygroups->count() > 0 && $request->get('account_type') == 'student') {
                $studygroups->each(function ($studygroup) use ($user) {
                    $studygroup->students()->attach($user->userable);
                    $studygroup->save();
                });
            }
        }

        // Format the final response in a desirable format
        return response()->json([
            'token' => $user->createToken('Via entree')->accessToken,
            'from' => $_SERVER['HTTP_X_REAL_IP'] ?? $_SERVER['REMOTE_ADDR'],
        ], 200);
    }

    /**
     * Fetch classes and/or academic levels
     * return those to Entree
     *
     * @param Request $request
     * @param string $field
     */
    public function fetch(Request $request, string $field)
    {
        $brin = $request->get('brin');
        $school = School::where('brin', '=', $brin)->firstOrFail();

        if ($field == 'study-groups') {
            // Return classes of the school
            $studygroups = $school->classes->where('join_till', '>=', Carbon::now());
            $studygroups = $studygroups->merge($school->classes->where('join_till', '==', null));
            return response()->json(
                $studygroups->sortBy('name')->pluck('name'),
                200
            );
        } elseif ($field == 'academic-levels') {
            $studygroup = $request->get('study_group');
            $levels = $school->classes()->where('name', '=', $studygroup)->firstOrFail()->academic_levels;

            // If no levels have been set, show an error
            if (!$levels) {
                $levels = ['Er zijn (nog) geen niveau\'s ingesteld voor deze klas.'];
            }

            // Return levels of the class
            return response()->json(
                $levels,
                200
            );
        }
    }

    /**
     * Check if a user is already known,
     * return it to Entree
     *
     * @param Request $request
     */
    public function userExists(Request $request)
    {
        $count = User::where('entree_uid', '=', $request->get('entree_uid'))
            ->count();

        return response()->json([
            'exists' => $count > 0
        ], 200);
    }
}