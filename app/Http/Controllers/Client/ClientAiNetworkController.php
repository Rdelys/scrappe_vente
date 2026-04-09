<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Client;
use App\Models\AiGeneration;
use App\Models\AiGenerationImage;
use Illuminate\Support\Facades\Storage;

class ClientAiNetworkController extends Controller
{

    public function index()
{
    $generations = \App\Models\AiGeneration::with('images')
        ->where('user_id', session('client.id'))
        ->latest()
        ->get();

    $lastImage = null;

    if ($generations->first() && $generations->first()->images->first()) {
        $lastImage = asset('storage/'.$generations->first()->images->first()->image_path);
    }

    return view('client.reseau-ia', compact('generations','lastImage'));
}

    public function generate(Request $request)
    {

        $request->validate([
            'network' => 'required',
            'prompt' => 'required',
            'person_image' => 'nullable|image'
        ]);

        $client = \OpenAI::client(config('openai.api_key'));

        $network = $request->network;

        $prompt = "Créer une publicité pour $network : ".$request->prompt;

        /*
        |--------------------------------------------------------------------------
        | TEXTE PUBLICITAIRE
        |--------------------------------------------------------------------------
        */

        $text = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ]);

        $generatedText = $text->choices[0]->message->content;

        /*
        |--------------------------------------------------------------------------
        | IMAGE PUBLICITAIRE
        |--------------------------------------------------------------------------
        */

        $imagePrompt = "
image publicitaire réaliste pour une publication sur $network.

la scène doit illustrer ce message publicitaire :
{$request->prompt}

photo professionnelle réaliste,
personnes réelles dans un environnement de travail ou de bureau,
émotions naturelles et situation crédible,
style storytelling marketing,
composition propre avec espace pour ajouter du texte publicitaire,
style publicité moderne pour réseaux sociaux,
aucun texte générique comme 'marketing digital',
pas d’icônes marketing ou de graphiques abstraits,
ambiance réaliste et professionnelle.
";

        $image = $client->images()->create([
    'model' => 'gpt-image-1',
    'prompt' => $imagePrompt,
    'size' => '1024x1024'
]);

        $imageBase64 = $image->data[0]->b64_json;

        $imageData = base64_decode($imageBase64);

        $fileName = 'ai/'.uniqid().'.png';

        Storage::disk('public')->put($fileName,$imageData);

        /*
        |--------------------------------------------------------------------------
        | DATABASE
        |--------------------------------------------------------------------------
        */

        $generation = AiGeneration::create([
    'user_id' => session('client.id'),
            'network'=>$network,
            'prompt'=>$request->prompt,
            'generated_text'=>$generatedText
        ]);

        AiGenerationImage::create([
            'generation_id'=>$generation->id,
            'image_path'=>$fileName
        ]);

        return back()->with([
            'text'=>$generatedText,
            'image'=>asset('storage/'.$fileName)
        ]);

    }
}