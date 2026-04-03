<?php

namespace App\Models;

use CodeIgniter\Model;

class CommunityPostModel extends Model
{
    protected $table = 'community_posts';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'channel_id',
        'post_text',
        'post_type',
        'media_url',
        'location',
        'likes_count',
        'comments_count',
        'shares_count',
        'visibility',
        'status',
        'user_type'
    ];

    protected $useTimestamps = true;
}
