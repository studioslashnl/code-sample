<?php
// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose.

namespace App\Http\Controllers\API;

use App\Http\Resources\MissionDetailResource;
use App\Http\Resources\MissionProgressDetailResource;
use App\Mission;
use App\Http\Requests\StoreMission;
use App\Http\Requests\UpdateMission;
use App\Http\Resources\MissionCollection;
use App\Services\LevelService;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Put;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;

class MissionController extends Controller
{
    /**
     * Check authorization first.
     */
    public function __construct()
    {
        $this->authorizeResource(Mission::class, 'mission');
    }

    /**
     * @Get(
     *     path="/missions",
     *     tags={"missions"},
     *     summary="Display a listing of the missions.",
     *     operationId="missionIndex",
     *     @Parameter(ref="#/components/parameters/page"),
     *     @Parameter(ref="#/components/parameters/limit"),
     *     @Response(
     *         response="200",
     *         description="successful operation",
     *         @JsonContent(ref="#/components/schemas/MissionCollection"),
     *     )
     * )
     *
     * Display a listing of the missions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $missions = Mission::orderBy('mission', 'ASC')->paginate();

        $data = new MissionCollection($missions);
        return response()->json($data, 200);
    }

    /**
     * @Get(
     *     path="/missions/progress/{missionId}",
     *     tags={"missions"},
     *     summary="Get student progress for a given mission.",
     *     operationId="missionProgress",
     *     @Parameter(
     *          name="uuid",
     *          description="Mission uuid",
     *          required=true,
     *          in="path",
     *          @Schema(
     *              type="string"
     *          )),
     *     @Response(
     *         response="200",
     *         description="successful operation",
     *         @JsonContent(ref="#/components/schemas/MissionProgressDetailResponse"),
     *     )
     * )
     *
     * Display mission progress for a given chapter.
     *
     * @param string $missionUuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function showProgressByMission(string $missionUuid)
    {
        $mission = Mission::findOrFail($missionUuid);

        $user = \Auth::user();

        if (!$user || !$user->getIsTeacherAttribute()) {
            return response()->json([
                'error' => 'Je moet een leraar zijn om deze resultaten op kunnen te vragen.',
            ], 404);
        }

        $chapters = $mission->chapters()->orderBy('chapter')->with(['levels' => function ($query) {
            return $query->orderBy('level');
        }])->withCount('levels')->get();

        $classes = $user->userable->classes()->with(['students', 'students.user'])->whereHas('missions', function (Builder $query) use ($mission) {
            $query->where('missions.id', $mission->id);
        })->get();

        $mission->studyGroups = $classes;
        $mission->students = $classes->pluck('students')->flatten()->map(function ($student) use ($chapters) {
            $student->chapters = $chapters;
            return $student;
        });

        $levels = $chapters->pluck('levels')->flatten()->map(function ($level) use ($mission) {
            $level->finished_students = LevelService::make($level)->getFinishedStudentsForLevel($mission->students);
            return $level;
        });

        $mission->finishedStudents = $levels->last()->finished_students;
        $mission->startedStudents = LevelService::make($levels->first())->getStartedStudentsForLevel($mission->students, $mission->finishedStudents);

        $data = MissionProgressDetailResource::make($mission);

        return response()->json($data, 200);
    }

    /**
     * @Post(
     *     deprecated=true,
     *     path="/missions",
     *     tags={"missions"},
     *     summary="Store a newly created mission",
     *     operationId="missionStore",
     *     @RequestBody(
     *          @MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @Schema(ref="#/components/schemas/MissionRequest")
     *          )
     *      ),
     *     @Response(
     *          response="201",
     *          description="successful operation",
     *          @JsonContent(
     *              @Schema(
     *                  type="array",
     *                  @Items(ref="#/components/schemas/MissionResponse")
     *              )
     *          )
     *      ),
     *      @Response(
     *          response="422",
     *          ref="#/components/responses/422"
     *      )
     * )
     *
     * Store a newly created mission in storage.
     *
     * @param \App\Http\Requests\UpdateMission $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMission $request)
    {
        $validated = $request->validated();

        $mission = Mission::create($validated);

        $data = MissionDetailResource::make($mission);

        return response()->json($data, 201);
    }

    /**
     * @Get(
     *     path="/missions/{missionAbbreviation}",
     *     tags={"missions"},
     *     summary="Display the specified mission.",
     *     operationId="missionShow",
     *     @Parameter(ref="#/components/parameters/missionAbbreviation"),
     *     @Response(
     *         response="200",
     *         description="successful operation",
     *         @JsonContent(ref="#/components/schemas/MissionDetailResponse"),
     *     )
     * )
     *
     * Display the specified mission.
     *
     * @param \App\Mission $mission
     * @return \App\Http\Resources\MissionResource
     */
    public function show(Mission $mission)
    {
        $data = MissionDetailResource::make($mission);

        return response()->json($data, 200);
    }

    /**
     * @Put(
     *     deprecated=true,
     *     path="/missions/{missionAbbreviation}",
     *     tags={"missions"},
     *     summary="Update the specified mission in storage.",
     *     operationId="missionUpdate",
     *     @Parameter(ref="#/components/parameters/missionAbbreviation"),
     *     @RequestBody(
     *          @MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @Schema(ref="#/components/schemas/MissionRequest")
     *          )
     *      ),
     *     @Response(
     *          response="200",
     *          description="successful operation",
     *          @JsonContent(
     *              @Schema(
     *                  type="array",
     *                  @Items(ref="#/components/schemas/MissionResponse")
     *              )
     *          )
     *      ),
     *      @Response(
     *          response="422",
     *          ref="#/components/responses/422"
     *      )
     * )
     *
     * Update the specified mission in storage.
     *
     * @param \App\Http\Requests\UpdateMission $request
     * @param \App\Mission $mission
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateMission $request, Mission $mission)
    {
        $validated = $request->validated();

        $data = MissionDetailResource::make($mission);

        return response()->json($data, 200);
    }

    /**
     * @Delete(
     *     deprecated=true,
     *     path="/missions/{missionAbbreviation}",
     *     tags={"missions"},
     *     summary="Remove the specified mission from storage.",
     *     operationId="missionDelete",
     *     @Parameter(ref="#/components/parameters/missionAbbreviation"),
     *     @Response(
     *          response="204",
     *          description="successful operation"
     *      ),
     *     @Response(
     *          response="422",
     *          description="Invalid data",
     *          @JsonContent(
     *              allOf={
     *                  @Schema(
     *                     type="object",
     *                     @Property(property="message", type="string",example="The given data was invalid"),
     *                     @Property(
     *                          property="errors",
     *                          type="object",
     *                              @Property(property="field", type="array",@Items(type="string")),
     *                      )
     *                  )
     *              }
     *          )
     *      )
     * )
     *
     * Remove the specified mission from storage.
     *
     * @param \App\Mission $mission
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Mission $mission)
    {
        $mission->delete();

        return response()->json([], 204);
    }
}