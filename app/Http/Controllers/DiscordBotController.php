<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DiscordService;
use Illuminate\Support\Facades\Log;

class DiscordBotController extends Controller
{
    protected $discordService;

    public function __construct(DiscordService $discordService)
    {
        $this->discordService = $discordService;
    }

    // Get Bot Information
    public function getBotInfo()
    {
        $response = $this->discordService->getBotInfo();
        return response()->json($response);
    }

    // Send a Message to a Channel
    public function sendMessage(Request $request)
    {
        $request->validate([
            'channel_id' => 'required|string',
            'message' => 'required|string',
        ]);

        $response = $this->discordService->sendMessage($request->channel_id, $request->message);
        return response()->json($response);
    }

    // Fetch Roles in a Guild
    public function getRoles()
    {
        $guildId = env('DISCORD_GUILD_ID');
        $response = $this->discordService->getRoles($guildId);
        return response()->json($response);
    }

    // Assign a Role to a User
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'role_id' => 'required|string',
        ]);

        $guildId = env('DISCORD_GUILD_ID');
        $response = $this->discordService->assignRole($guildId, $request->user_id, $request->role_id);
        return response()->json($response);
    }


    // remove a Role to a User
    public function removeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'role_id' => 'required|string',
        ]);

        $guildId = env('DISCORD_GUILD_ID');
        $response = $this->discordService->removeRole($guildId, $request->user_id, $request->role_id);
        return response()->json($response);
    }

    public function getChannels()
    {
        $guildId = env('DISCORD_GUILD_ID');
        $channels = $this->discordService->getChannels($guildId);
        return response()->json($channels);
    }

    public function getUsers()
    {
        $guildId = env('DISCORD_GUILD_ID');
        if (!is_numeric($guildId)) {
            return response()->json(['error' => 'Invalid Guild ID'], 400);
        }

        $users = $this->discordService->getUsers($guildId);
        return response()->json($users);
    }


    // public function subscribed(Request $request)
    // {
    //     $data  =$request->all();
    //     dd($data['billing']['discord_id']);
    //     $role_id = '1315696189684973698';


    //     $guildId = env('DISCORD_GUILD_ID');

    //     return $this->discordService->assignRole($guildId, $data['billing']['discord_id'], $role_id);
    // }

    public function unSubscribed(Request $request)
    {
        $data  = $request->all();
if(isset($data['webhook_id'])){
    return true;
}
        Log::info('web-hook:', $data);

        $discord_id = null;
        $meta_data = collect($data['meta_data'])->where('key', 'discord_id')->first()??null;

        if($meta_data && $meta_data['value']){
            $discord_id = $meta_data['value'];
        }

        if(isset($data['billing']['billing_discord_id'])){
            $discord_id = $data['billing']['billing_discord_id'];
        }


        $guildId = env('DISCORD_GUILD_ID');

        if ($discord_id) {
            if ($data['status'] == 'on-hold') {
                $response = $this->discordService->removeRole($guildId, $discord_id, env('ACTIVE_ROLE_ID'));
            }
            if ($data['status'] == 'active') {
                $response = $this->discordService->assignRole($guildId, $discord_id, env('ACTIVE_ROLE_ID'));
            }
        }
        return response()->json($response);
    }
}
