<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DiscordService
{
    protected $baseUrl = 'https://discord.com/api/v10';

    /**
     * Retrieves bot information from Discord.
     *
     * @return array The bot information from the Discord API.
     */
    public function getBotInfo()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
        ])->get("{$this->baseUrl}/users/@me");

        // Return the response as a JSON array
        return $response->json();
    }

    /**
     * Sends a message to the specified channel.
     *
     * @param string $channelId The ID of the Discord channel.
     * @param string $message The message to send.
     *
     * @return array The API response.
     */
    public function sendMessage($channelId, $message)
    {
        return Http::withHeaders([
            'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
        ])->post("{$this->baseUrl}/channels/{$channelId}/messages", [
            'content' => $message,
        ])->json();
    }

    /**
     * Retrieves all roles from a specified guild (server).
     *
     * @param string $guildId The ID of the Discord guild (server).
     *
     * @return array The list of roles in the guild.
     */
    public function getRoles($guildId)
    {
        return Http::withHeaders([
            'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
        ])->get("{$this->baseUrl}/guilds/{$guildId}/roles")->json();
    }

    /**
     * Assigns a role to a user in a guild (server).
     *
     * @param string $guildId The ID of the Discord guild (server).
     * @param string $userId The ID of the user to assign the role to.
     * @param string $roleId The ID of the role to assign.
     *
     * @return array The API response.
     */
    public function assignRole($guildId, $userId, $roleId)
    {
        return Http::withHeaders([
            'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
        ])->put("{$this->baseUrl}/guilds/{$guildId}/members/{$userId}/roles/{$roleId}")->json();
    }

        /**
     * Removes a role from a user in a guild (server).
     *
     * @param string $guildId The ID of the Discord guild (server).
     * @param string $userId The ID of the user to remove the role from.
     * @param string $roleId The ID of the role to remove.
     *
     * @return array The API response.
     */
    public function removeRole($guildId, $userId, $roleId)
    {
        return Http::withHeaders([
            'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
        ])->delete("{$this->baseUrl}/guilds/{$guildId}/members/{$userId}/roles/{$roleId}")->json();
    }

     /**
     * Retrieves all channels from a specified guild (server).
     *
     * @param string $guildId The ID of the Discord guild (server).
     *
     * @return array The list of channels in the guild.
     */
    public function getChannels($guildId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
        ])->get("{$this->baseUrl}/guilds/{$guildId}/channels");

        // Return the response as a JSON array
        return $response->json();
    }

    public function getUsers($guildId, $limit = 1000)
    {
        return Http::withHeaders([
            'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
        ])->get("{$this->baseUrl}/guilds/{$guildId}/members", [
            'limit' => $limit,
        ])->json();
    }



    public function getAllOrders()
    {
        // WooCommerce API endpoint
        $url = 'https://instagramcollege.net/wp-json/wc/v3/orders';

        // WooCommerce API credentials
        $username = env('WP_CONSUMER_KEY'); // Consumer Key
        $password = env('WP_CONSUMER_SECRET'); // Consumer Secret

        // Make the request
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode("$username:$password"),
        ])->get($url, [
            'per_page' =>100, // Fetch up to 1000 orders
        ]);


        // Return the response as an array
        return $response->json();
    }

}
