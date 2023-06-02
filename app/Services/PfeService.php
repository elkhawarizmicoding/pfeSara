<?php

namespace App\Services;

use App\Http\Resources\EventForMargeResource;
use App\Http\Resources\EventPassViewResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\TermEventPassResource;
use App\Http\Resources\TermForMargeResource;
use App\Http\Resources\TermTermResource;
use App\Http\Resources\TermViewResource;
use App\Models\Document;
use App\Models\EventPass;
use App\Models\Profile;
use App\Models\ProfileTerm;
use App\Models\ProfileTermEventPass;
use App\Models\ProfileTermTerm;
use App\Models\Term;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class PfeService
{
    public function index(){
        $pProfile = Profile::with(['terms', 'eventPasses'])->find(Auth::id());
        return [
            'terms' => TermViewResource::collection($pProfile->terms),
            'events' => EventPassViewResource::collection($pProfile->eventPasses),
        ];
    }
    //================================================= Application[Store data to database ] =================================
    public function storeData($dataCsv){
        foreach ($dataCsv as $data){
            $profile = Profile::query()->create([
                'full_name' => $data->full_name,
                'area' => $data->area,
                'specialty' => $data->specialty,
                'age' => $data->age,
                'email' => $data->email,
                'phone' => $data->phone,
                'sex' => $data->sex,
                'password' => Hash::make('aze123@'),
                'nb_docs' => $data->docs,
                'freq_max' => $data->freq_max,
            ]);
            $idsDocs = [];
            foreach (range(1, $data->docs) as $index){
                $foreachDoc = Document::query()->create([
                    'title' => 'Le Domaine est '.$profile->area_fr.' Spécialité['.$profile->specialty.'] => Docs('.$index.')',
                    'date' => date('Y-m-d'),
                    'profile_id' => $profile->id,
                ]);
                $obj = new \stdClass();
                $obj->index = $index;
                $obj->id_doc = $foreachDoc->id;
                $idsDocs[] = $obj;
            }
            $profile = Profile::with('documents')->find($profile->id);
            $profile->update([
                'nb_docs' => $profile->documents->count()
            ]);
            /*
             * TODO:: All actions of term
             */
            $terms = $this->splitTerms($data->terms);
            foreach ($terms as $item){
                $term = Term::query()->create([
                    'term_name' => strtolower($item->name)
                ]);
                $freqTotal = collect($item->docs)->sum('count');
                foreach ($item->docs as $doc){
                    $idDocFromObj = collect($idsDocs)->filter(function ($itemDoc) use ($doc){
                        return $itemDoc->index == $doc->id;
                    })->first();
                    if($idDocFromObj != null){
                        $document = Document::query()->find($idDocFromObj->id_doc);
                        $document->terms()->attach($term->id, ['freq' => $doc->count]);
                    }
                }

                $pProfileTerm = ProfileTerm::query()->where([
                    ['profile_id', $profile->id],
                    ['term_id', $term->id],
                ]);
                if($pProfileTerm->exists()){
                    $pProfileTerm = $pProfileTerm->first();
                    $pProfileTerm->freq_total = $pProfileTerm->freq_total + $freqTotal;
                    $pProfileTerm->np_docs = $pProfileTerm->np_docs++;
                    $pProfileTerm->weight = $this->calcWeightTerm($profile->freq_max, $pProfileTerm->freq_total, $profile->nb_docs, $pProfileTerm->np_docs);
                    $pProfileTerm->save();
                }
                $profile->terms()->attach($term->id, [
                    'freq_total' => $freqTotal,
                    'np_docs' => count($item->docs),
                    'weight' => $this->calcWeightTerm($profile->freq_max, $freqTotal, $profile->nb_docs, count($item->docs))
                ]);
            }
            $profile = Profile::with('terms')->find($profile->id);
            $termIds = [];
            foreach ($profile->terms as $term1){
                $termIds[] = $term1->id;
                $term1 = Term::with('documents')->find($term1->id);
                foreach (collect($profile->terms)->whereNotIn('id', $termIds)->all() as $term2){
                    $term2 = Term::with('documents')->find($term2->id);
                    $nbDocsCom = count(array_intersect(
                        collect($term1->documents)->pluck('id')->all(),
                        collect($term2->documents)->pluck('id')->all()
                    ));
                    $profile->termTerms()->attach($term1->id, [
                        'term2_id' => $term2->id,
                        'nb_docs_com' => $nbDocsCom,
                        'sim_tt' => $this->calcSimTermTerm($term1->documents->count(), $term2->documents->count(), $nbDocsCom),
                    ]);
                }
            }
            /*
             * TODO:: All actions of event
             */
            $events = $this->splitEvents($data->events);
            $profile = Profile::with(['documents', 'terms'])->find($profile->id);
            foreach ($events as $event){
                $docs = $event['docs'];
                unset($event['docs']);
                $pEventPass = EventPass::query()->create($event);
                $docsData = [];
                foreach (collect($docs)->pluck('id')->all() as $id){
                    $docsData[] = collect($idsDocs)->filter(function ($itemDoc) use ($id){
                        return $itemDoc->index == $id;
                    })->first();
                }
                $documentIds = collect($docsData)->pluck('id_doc')->all();
                $pEventPass->documents()->attach($documentIds);
                $calcWeightEventPass = $this->calcWeightEventPass(count($docs), $profile->nb_docs);
                $profile->eventPasses()->attach($pEventPass->id, [
                    'weight' => $calcWeightEventPass,
                    'np_docs' => count($docs),
                ]);
                $pEventPass = EventPass::with('documents')->find($pEventPass->id);
                foreach ($profile->terms as $term){
                    $nbDocsCom = count(array_intersect(
                        collect($term->documents)->pluck('id')->all(),
                        collect($pEventPass->documents)->pluck('id')->all()
                    ));
                    ProfileTermEventPass::query()->create([
                        'profile_id' => $profile->id,
                        'term_id' => $term->id,
                        'event_pass_id' => $pEventPass->id,
                        'nb_docs_com' => $nbDocsCom,
                        'sim_te' => $this->calcSimTermEventPass(
                            $term->pivot->weight,
                            $calcWeightEventPass,
                            $nbDocsCom,
                            count($term->documents),
                            count($pEventPass->documents),
                        )
                    ]);
                }
            }
        }
    }
    public function calcWeightTerm($freqMax, $freqTotal, $nbDocsP, $nbDocsPT){
        return (0.5 + 0.5 * ($freqTotal/$freqMax)) * log10($nbDocsP/$nbDocsPT);
    }
    public function calcSimTermTerm($nbDocsTerm1, $nbDocsTerm2, $nbDocsTermCom){
        return $nbDocsTermCom / ($nbDocsTerm1 + $nbDocsTerm2);
    }
    public function calcWeightEventPass($nbDocsEvent, $nbDocsProfile){
        return $nbDocsEvent / $nbDocsProfile;
    }
    public function calcSimTermEventPass($weightTerm, $weightEventPass, $nbDocsCom, $nbDocsTerm, $nbDocsEventPass){
        return ($weightTerm * $weightEventPass * $nbDocsCom) / ($nbDocsTerm + $nbDocsEventPass);
    }
    private function splitTerms($str){
        $terms = explode(';', $str);
        $dataTerms = [];
        foreach ($terms as $term){
            $split = explode('@', $term);
            $obj = new \stdClass();
            $obj->name = $split[0];
            $obj->docs = [];
            if(str_contains($split[1], '|')){
                foreach (explode('|', $split[1]) as $doc){
                    $splitDoc = explode(':', $doc);
                    $objDoc = new \stdClass();
                    $objDoc->id = $splitDoc[0];
                    $objDoc->count = $splitDoc[1];
                    $obj->docs[] = $objDoc;
                }
            }else{
                $splitDoc = explode(':', $split[1]);
                $objDoc = new \stdClass();
                $objDoc->id = $splitDoc[0];
                $objDoc->count = $splitDoc[1];
                $obj->docs[] = $objDoc;
            }
            $dataTerms[] = $obj;
        }
        return $dataTerms;
    }
    private function splitEvents($str){
        if(strlen($str) == 0){
            return [];
        }
        $data = [];
        foreach (str_contains($str, ';') ? explode(';', $str) : [$str] as $event){
            $split = explode('|', $event);
            $eventData['name'] = $split[0];
            $eventData['date'] = $split[1];
            $eventData['lieu'] = $split[2];
            $eventData['docs'] = [];
            foreach (explode('@', $split[3]) as $doc){
                $obj = new \stdClass();
                $obj->id = explode(':', $doc)[0];
                $obj->count = explode(':', $doc)[1];
                $eventData['docs'][] = $obj;
            }
            $data[] = $eventData;
        }
        return  $data;
    }
    //================================================= Application[Auth] ====================================================
    public function login($data){
        $pProfile = Profile::with(['documents',
            'terms.documents',
            'eventPasses.documents',
            'termTerms',
            'termEventPasses'])->where('email', $data['email']);
        if(!$pProfile->exists()){
            return [
                'status' => false,
                'message' => 'Account invalid',
            ];
        }
        $pProfile = $pProfile->first();
        if(!password_verify($data['password'], $pProfile->getAttribute('password'))){
            return [
                'status' => false,
                'message' => 'Account invalid',
            ];
        }
        return [
            'status'=> true,
            'token' => $pProfile->createToken('token')->plainTextToken,
            'info' => new ProfileResource($pProfile)
        ];
    }
    public function inscription($data){
        try {
            $data['password'] = Hash::make($data['password']);
            Profile::query()->create($data);
            return [
                'status'=> true,
            ];
        }catch (\Exception $e){
            return [
                'status'=> false,
            ];
        }
    }
    //================================================= Application ==========================================================
    public function searchPersonalized($query){
        $pProfile = Profile::with([
            'documents',
            'terms.documents',
            'eventPasses.documents',
            'termTerms',
            'termEventPasses'
        ])->find(Auth::id());
        $pTerm = $pProfile->terms->where('term_name', strtolower($query));
        if($pTerm->count()){
            $pTerm = $pTerm->first();
            $pTerm = Term::with('documents')->find($pTerm->id);
            $weightTerm = $this->calcWeightTerm($pProfile->freq_max, 1, $pProfile->nb_docs, $pTerm->documents->count());
            $dataTerms = [];
            foreach ($pProfile->terms as $term){
                if(strtolower($term->term_name) != strtolower($query)){
                    $obj = new \stdClass();
                    $obj->query = $query;
                    $obj->term = $term->term_name;
                    $obj->nb_docs_com = count(array_intersect(
                        collect($term->documents)->pluck('id')->all(),
                        collect($pTerm->documents)->pluck('id')->all(),
                    ));
                    $obj->calc = $this->calcSimTermTerm($term->documents->count(), $pTerm->documents->count(), $obj->nb_docs_com);
                    $obj->nb_docs_query = $pTerm->documents->count();
                    $obj->nb_docs_term = $term->documents->count();

                    $dataTerms[] = $obj;
                }
            }
            $dataEvents = [];
            foreach ($pProfile->eventPasses as $eventPass){
                $weightEventPass = $this->calcWeightEventPass($eventPass->documents->count(), $pProfile->nb_docs);
                $obj = new \stdClass();
                $obj->query = $query;
                $obj->event_pass = $eventPass->name;
                $obj->nb_docs_com = count(array_intersect(
                    collect($eventPass->documents)->pluck('id')->all(),
                    collect($pTerm->documents)->pluck('id')->all(),
                ));
                $obj->calc = $this->calcSimTermEventPass(
                    $weightTerm,
                    $weightEventPass,
                    $obj->nb_docs_com,
                    $pTerm->documents->count(),
                    $eventPass->documents->count(),
                );

                $obj->nb_docs_query = $pTerm->documents->count();
                $obj->nb_docs_event = $eventPass->documents->count();
                $obj->weight_query = $weightTerm;
                $obj->weight_event = $weightEventPass;

                $dataEvents[] = $obj;
            }

            $dataTermsStored = [];
            foreach (collect($dataTerms)->sortByDesc('calc') as $item){
                $dataTermsStored[] = $item;
            }
            $dataEventsStored = [];
            foreach (collect($dataEvents)->sortByDesc('calc') as $item){
                $dataEventsStored[] = $item;
            }

            $sumTerms = collect($dataTerms)->sum('calc');
            $sumEvents = collect($dataEvents)->sum('calc');

            $obj = new \stdClass();
            $obj->first = $sumTerms > $sumEvents ? $dataTermsStored : $dataEventsStored;
            $obj->last = $sumEvents > $sumTerms ? $dataTermsStored : $dataEventsStored;

            return [
                'status' => true,
                'query' => $query,
                'sum_terms' => $sumTerms,
                'sum_events' => $sumEvents,
                'selected_term' => $sumTerms > $sumEvents,
                'selected_event' => $sumEvents > $sumTerms,
                'data' => $obj,
            ];
        }
        return [
            "status" => false,
            "message" => "Ce terme est introuvable"
        ];
    }
    /*public function searchPersonalizedV1($query){
        $pProfile = Profile::with([
            'documents',
            'terms.documents',
            'eventPasses.documents',
            'termTerms',
            //'termTerms.term2',
            'termEventPasses'
        ])->find(Auth::id());
        $idsDocs = $pProfile->documents->pluck('id')->all();
        $idsDocs = [
            $idsDocs[count($idsDocs) - 1],
            $idsDocs[count($idsDocs) - 1] - 2,
        ];
        $weightTerm = $this->calcWeightTerm($pProfile->freq_max, 1, $pProfile->nb_docs, count($idsDocs));

        $dataTerms = [];
        $dataResponse = [];
        foreach ($pProfile->terms as $term){
            $obj = new \stdClass();
            $obj->query = $query;
            $obj->term = $term->term_name;
            $obj->nb_docs_com = count(array_intersect(
                collect($term->documents)->pluck('id')->all(),
                $idsDocs
            ));
            $obj->calc = $this->calcSimTermTerm($term->documents->count(), count($idsDocs), $obj->nb_docs_com);

            $obj->nb_docs_query = count($idsDocs);
            $obj->nb_docs_term = $term->documents->count();

            $dataTerms[] = $obj;
            $dataResponse[] = $obj;
        }
        $dataEvents = [];
        foreach ($pProfile->eventPasses as $eventPass){
            $weightEventPass = $this->calcWeightEventPass($eventPass->documents->count(), $pProfile->nb_docs);
            $obj = new \stdClass();
            $obj->query = $query;
            $obj->event_pass = $eventPass->name;
            $obj->nb_docs_com = count(array_intersect(
                collect($eventPass->documents)->pluck('id')->all(),
                $idsDocs
            ));
            $obj->calc = $this->calcSimTermEventPass(
                $weightTerm,
                $weightEventPass,
                $obj->nb_docs_com,
                count($idsDocs),
                count($eventPass->documents),
            );

            $obj->nb_docs_query = count($idsDocs);
            $obj->nb_docs_event = $eventPass->documents->count();
            $obj->weight_query = $weightTerm;
            $obj->weight_event = $weightEventPass;


            $dataEvents[] = $obj;
            $dataResponse[] = $obj;
        }

        $data = [];
        foreach (collect($dataResponse)->sortByDesc('calc') as $item){
            $data[] = $item;
        }

        $sumTerms = collect($dataTerms)->sum('calc');
        $sumEvents = collect($dataEvents)->sum('calc');

        return [
            'status' => true,
            'query' => $query,
            'terms' => TermViewResource::collection($pProfile->terms),
            'events' => EventPassViewResource::collection($pProfile->eventPasses),
            'sum_terms' => $sumTerms,
            'sum_events' => $sumEvents,
            'selected_term' => $sumTerms > $sumEvents,
            'selected_event' => $sumEvents > $sumTerms,
            'data' => $data
        ];
    }*/
    public function searchV1($query){
        $query = strtolower($query);
        $pProfile = Profile::with([
            'documents',
            'terms',
            'eventPasses',
            'termTerms',
            //'termTerms.term2',
            'termEventPasses'
        ])->find(Auth::id());
        //Check query if exist in terms
        $term = Term::query()->where('term_name', $query);
        if(!$term->exists()){
            $term = Term::query()->create(['term_name' => $query]);
            $this->attachTermDocumentProfile($pProfile, $term);
        }else{
            $term = $term->first();
            if(!collect($pProfile->terms)->contains('id', $term->id)){
                $this->attachTermDocumentProfile($pProfile, $term);
            }
        }
        $pProfile = Profile::with([
            'documents',
            'terms',
            'eventPasses',
            'termTerms',
            'termEventPasses'
        ])->find(Auth::id());
        $dataTerms = collect($pProfile->termTerms)->filter(function ($item) use($term){
            return $item->pivot->term2_id == $term->id;
        });
        $dataTerms = collect(TermForMargeResource::collection($dataTerms))->sortByDesc('sim_tt');
        $dataEvent = collect($pProfile->termEventPasses)->filter(function ($item) use ($term){
            return $item->pivot->term_id == $term->id;
        });
        $dataEvent = collect(EventForMargeResource::collection($dataEvent))->sortByDesc('sim_te');

        return [
            'sum_terms' => collect($dataTerms)->sum('calc'),
            'sum_events' => collect($dataEvent)->sum('calc'),
            'data' => Arr::sort(array_merge($dataTerms->toArray(), $dataEvent->toArray()), function ($item){
                return $item->calc;
            })
        ];

//        return [
//            "status" => true,
//            "query" => $query,
//            /*"profile_data" => new ProfileResource(Profile::with([
//                'documents',
//                'terms',
//                'eventPasses',
//                'termTerms',
//                //'termTerms.term2',
//                'termEventPasses'
//            ])->find(Auth::id())),*/
//            /*"top_search_term" => $dataTerms,
//            "top_search_event" => $dataEvent,*/
//            "top_search" =>
//        ];
    }
    private function attachTermDocumentProfile($pProfile, $term){
        $idsDocs = $pProfile->documents->pluck('id')->all();
        $term->documents()->attach($idsDocs[count($idsDocs) - 1], [
            'freq' => 1,
        ]);
        $term->documents()->attach($idsDocs[count($idsDocs) - 1] - 2, [
            'freq' => 1,
        ]);

        $pProfile->terms()->attach($term->id, [
            'freq_total' => rand(2, 5),
            'np_docs' => count($term->documents),
            'weight' => $this->calcWeightTerm($pProfile->freq_max, 1, $pProfile->nb_docs, 1)
        ]);
        $term = Term::with('documents')->find($term->id);
        foreach ($pProfile->terms as $term1) {
            $term1 = Term::with(['documents'])->find($term1->id);
            $nbDocsCom = count(array_intersect(
                collect($term1->documents)->pluck('id')->all(),
                collect($term->documents)->pluck('id')->all()
            ));
            $pProfile->termTerms()->attach($term1->id, [
                'term2_id' => $term->id,
                'nb_docs_com' => $nbDocsCom,
                'sim_tt' => $this->calcSimTermTerm($term1->documents->count(), $term->documents->count(), $nbDocsCom),
            ]);
        }
        $tProfile = Profile::with(['terms.documents', 'eventPasses'])->find($pProfile->id);
        $term = $tProfile->terms->where('term_name', $term->term_name)->first();
        foreach ($tProfile->eventPasses as $eventPass){
            $nbDocsCom = count(array_intersect(
                collect($term->documents)->pluck('id')->all(),
                collect($eventPass->documents)->pluck('id')->all()
            ));
            ProfileTermEventPass::query()->create([
                'profile_id' => $tProfile->id,
                'term_id' => $term->id,
                'event_pass_id' => $eventPass->id,
                'nb_docs_com' => $nbDocsCom,
                'sim_te' => $this->calcSimTermEventPass(
                    $term->pivot->weight,
                    $eventPass->pivot->weight,
                    $nbDocsCom,
                    count($term->documents),
                    count($eventPass->documents),
                )
            ]);
        }
    }
    //=========================================================================================
    public function searchClassic($query){
        $apiKey = '79490538637532ce5d789e313a72e633da5bc6879d07d4c38b4a3b4f6af3c569';
        $url = 'https://serpapi.com/search.json?q='.$query.'&location=Algeria&hl=fr&gl=fr&google_domain=google.com&api_key='.$apiKey;

        $response = Http::withOptions([
            'verify' => false
        ])->accept('application/json')->get($url);

        return [
            'status' => true,
            'data' => collect(json_decode($response)->organic_results)
        ];
    }
    public function updateProfile($data){
        $pProfile = Profile::with(['documents',
            'terms.documents',
            'eventPasses.documents',
            'termTerms',
            'termEventPasses'])->find(Auth::id());
        $pProfile->update($data);

        return [
            'status' => true,
            'info' => new ProfileResource($pProfile)
        ];
    }
}
