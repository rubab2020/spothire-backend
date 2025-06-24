<?php

namespace App\Http\Controllers;

use App\Project;
use App\Rating;
use App\Skill;
use App\Tag;
use App\User;
use App\workExperience;
use Illuminate\Http\Request;

class SkillController extends ApiController
{
    public function getSkills(Request $request){
        $user           = auth()->guard('api')->authenticate();
        $tags           = Tag::all();
        $skills         = Skill::where('reg_id',$user->id)
                                ->whereNull('work_id')
                                ->get();

        $userBasicInfo = null;
        $userData = User::find($user->id);
        if($userData){
            $userBasicInfo['email'] = $userData->email;
            $userBasicInfo['phone'] = $userData->phone;
            $userBasicInfo['is_email_visible'] = $userData->is_email_visible ? true : false;
            $userBasicInfo['is_phone_visible'] = $userData->is_phone_visible  ? true : false;
        }

        return $this->respond([
            'success' => true, 
            'skills' => $skills,
            'tags'=>$tags,
            'user_basic_info' => $userBasicInfo
        ]);
    }
    
    public function saveSkills(Request $request){
        $user        = JWTAuth::parseToken()->authenticate();
        $credentials = $request->all();
        $skills = Skill::where('reg_id',$user->id)
                    ->groupBy('tag_name')->get()->pluck('tag_name')->toArray();

        $deleted_result = array_diff($skills,$credentials['skill']);
        $added_skills = array_diff($credentials['skill'],$skills);

        foreach($deleted_result as $deleted){
            $skills_delete = Skill::where('reg_id',$user->id)
                                ->where('tag_name',$deleted)->delete();
        }
        foreach($added_skills as $added){
            $tags_count = Tag::where('tag_name',$added)->count();

            if($tags_count == 0){
                $Tag = new Tag();
                $Tag->name = $added;
                $Tag->save();
            }

            $skill_add  = new Skill();
            $skill_add->tag_name = $added;
            $skill_add->reg_id = $user->id;
            $skill_add->save();
        }

        return $this->respond($this->getExperienceAndProjectData($user));
    }

    public function getExperienceAndProjectData($user){
        $tags           = Tag::all();
        $workExperience = workExperience::with('skills')->where('reg_id',$user->id)->orderBy('period_from', 'desc')->get();
        $project        = Project::with('skills')->where('reg_id',$user->id)->get();
        $skills_used    = Skill::where('reg_id',$user->id)
                                ->whereNull('work_id')
                                ->get();

        $workExperience_formated = $this->addUserExperienceRating($workExperience);
        $skills_used_formated    = $this->addUserSkillRating($user->id,$skills_used,$workExperience);

        return[
            'success' => true,
            'experience' => $workExperience_formated,
            'tags'=> $tags,
            'skills'=>$skills_used_formated,
        ];
    }

    /**
     * return user experience rating
     * @param integer $userID
     * @param array $experiences
     *
     * @return array
     **/

    public function addUserExperienceRating($experiences)
    {
        foreach($experiences as $experience){
            $countRatedSkills = 0;
            $totalRatePoints = 0;

            foreach($experience->skills as $skill){
                $point = Rating::getPoint($experience->application_id, $skill->id);
                $skill->rating =  $point;

                if($point > 0){
                    $totalRatePoints += $point;
                    $countRatedSkills++;
                }
            }

            // adding overall_rating property
            $experience->overall_rating = $countRatedSkills > 0 ? ($totalRatePoints / $countRatedSkills) : 0;
        }
        return $experiences;
    }


    /**
     * return user skill rating
     * @param integer $userID
     * @param array $skills
     * @param array $experiences
     *
     * @return array
     **/
    public function addUserSkillRating($userID, $skills, $experiences)
    {
        $skillNames = [];
        foreach($skills as $skill){
            if( ! in_array($skill->tag_name, $skillNames)){
                $skillNames[] = $skill->tag_name;
            }
        }

        $results = [];
        foreach($skillNames as $skillName){
            $rating = $this->calcGlobalSillRating($experiences, $skillName);
            $results[] = [
                'id' => null,
                'tag_name' => $skillName,
                'rating' => number_format((float)$rating, 2, '.', '')
            ];
        }
        return $results;
    }


    /**
     * return calculated global skill rating baseed on experiences' skills' points
     * @param array $experiences
     * @param integer $skillID
     *
     * @return integer
     **/
    public function calcGlobalSillRating($experiences, $skillName)
    {
        $totalPoint = 0;
        $totalRatedExperience = 0;
        foreach($experiences as $experience){
            foreach($experience->skills as $skill){
                if($skill->tag_name == $skillName && $skill->rating > 0){
                    $totalPoint += $skill->rating;
                    $totalRatedExperience++;
                    break;
                }
            }
        }

        return $totalRatedExperience > 0 ? ($totalPoint / $totalRatedExperience) : 0;
    }
}
