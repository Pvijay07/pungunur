<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CommunityPostModel;
use App\Models\CommunityMediaModel;
use App\Models\CommunityLikeModel;
use App\Models\CommunityCommentModel;
use App\Models\CommunityCommentLikeModel;
use App\Models\CommunityChannelModel;
use App\Models\CommunityChannelJoinModel;
use App\Models\CommunityPostChannelModel;
use App\Models\CommunityPostTagModel;
use App\Models\CommunitySaveModel;
use App\Models\CommunityReportModel;
use App\Models\CommunityNotificationModel;
use App\Models\CommunityFollowModel;
use App\Models\CommunityPostDislikeModel;
use App\Models\CommunityCommentDislikeModel;
use App\Models\User;
use App\Libraries\PushNotify;
use Exception;
use Config\Database;

class CommunityController extends BaseController
{
  protected $postModel;
  protected $mediaModel;
  protected $likeModel;
  protected $commentModel;
  protected $commentLikeModel;
  protected $channelModel;
  protected $channelJoinModel;
  protected $postChannelModel;
  protected $postTagModel;
  protected $saveModel;
  protected $reportModel;
  protected $notificationModel;
  protected $followModel;
  protected $postDislikeModel;
  protected $commentDislikeModel;
  protected $db;
  protected $pushNotify;

  public function __construct()
  {
    $this->postModel           = new CommunityPostModel();
    $this->mediaModel          = new CommunityMediaModel();
    $this->likeModel           = new CommunityLikeModel();
    $this->commentModel        = new CommunityCommentModel();
    $this->commentLikeModel    = new CommunityCommentLikeModel();
    $this->channelModel        = new CommunityChannelModel();
    $this->channelJoinModel    = new CommunityChannelJoinModel();
    $this->postChannelModel    = new CommunityPostChannelModel();
    $this->postTagModel        = new CommunityPostTagModel();
    $this->saveModel           = new CommunitySaveModel();
    $this->reportModel         = new CommunityReportModel();
    $this->notificationModel   = new CommunityNotificationModel();
    $this->followModel         = new CommunityFollowModel();
    $this->postDislikeModel    = new CommunityPostDislikeModel();
    $this->commentDislikeModel = new CommunityCommentDislikeModel();
    $this->db                  = Database::connect ();
    $this->pushNotify          = new PushNotify();
  }

  /**
   * Standard API Response helper
   */
  protected function sendResponse( bool $status, string $message, $data = [], int $statusCode = 200 )
  {
    return $this->response->setJSON ( [
      'status'  => $status,
      'message' => $message,
      'data'    => $data
    ] )->setStatusCode ( $statusCode );
  }

  /**
   * Helper to format post data with related info
   */
  protected function formatPosts( array $posts, $userId = null ): array
  {
    foreach ( $posts as &$post ) {
      $postId = $post['id'];

      // Media
      $post['media'] = $this->mediaModel->where ( 'post_id', $postId )->findAll ();

      // Interaction Status
      $post['is_liked'] = $userId ? ( $this->likeModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->countAllResults () > 0 ) : false;
      $post['is_saved'] = $userId ? ( $this->saveModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->countAllResults () > 0 ) : false;

      // Profile Formatting - Standardizing on 'profile' column
      $profileFilename     = $post['profile'] ?? 'default.png';
      $post['profile_url'] = base_url ( 'public/uploads/users/' . $profileFilename );

      // Channel Info
      // First try to get it from the map table (legacy), otherwise use direct channel_id column
      $channelMap      = $this->postChannelModel->where ( 'post_id', $postId )->first ();
      $actualChannelId = $channelMap ? $channelMap['channel_id'] : ( $post['channel_id'] ?? null );

      if ( $actualChannelId ) {
        $post['channel'] = $this->channelModel->find ( $actualChannelId );
      }
      else {
        $post['channel'] = null;
      }

      // Remove raw profile filename as url is now provided
      unset ($post['profile']);
    }
    return $posts;
  }

  public function createPost()
  {
    // Log exhaustive request data for debugging
    log_message ( 'error', '[Community-Debug] POST Data: ' . json_encode ( $this->request->getPost () ) );
    log_message ( 'error', '[Community-Debug] ALL Files: ' . json_encode ( array_keys ( $this->request->getFiles () ) ) );

    $postType = $this->request->getPost ( 'post_type' );
    $postText = $this->request->getPost ( 'post_text' );

    // Handle Media Retrieval
    $mediaFiles = [];

    // 1. Try getFileMultiple 
    $multiple = $this->request->getFileMultiple ( 'media' );
    if ( $multiple ) {
      $mediaFiles = $multiple;
    }
    else {
      // 2. Try getFile 
      $single = $this->request->getFile ( 'media' );
      if ( $single ) {
        $mediaFiles = [$single];
      }
    }

    log_message ( 'error', '[Community-Debug] Detected ' . count ( $mediaFiles ) . ' media files' );

    // Auto-detect post type if not provided
    if ( empty ( $postType ) ) {
      $postType = !empty ( $mediaFiles ) ? 'image' : 'text';
    }

    $rules = [
      'user_id'    => 'required|numeric',
      'channel_id' => 'required|numeric',
      'post_text'  => 'permit_empty|string',
      'location'   => 'permit_empty|string',
    ];

    if ( !$this->validate ( $rules ) ) {
      return $this->sendResponse ( false, 'Validation failed', $this->validator->getErrors () );
    }

    $this->db->transStart ();
    try {
      $postId = $this->postModel->insert ( [
        'user_id'    => $this->request->getPost ( 'user_id' ),
        'post_text'  => $postText,
        'post_type'  => $postType,
        'location'   => $this->request->getPost ( 'location' ),
        'channel_id' => $this->request->getPost ( 'channel_id' ),
        'user_type'  => 'user',
        'status'     => 'active',
        'created_at' => date ( 'Y-m-d H:i:s' ),
        'updated_at' => date ( 'Y-m-d H:i:s' )
      ] );

      if ( !$postId ) throw new Exception( 'Failed to create post record' );

      // Map to channel
      $this->postChannelModel->insert ( [
        'post_id'    => $postId,
        'channel_id' => $this->request->getPost ( 'channel_id' )
      ] );


      if ( $mediaFiles ) {
        foreach ( $mediaFiles as $file ) {
          if ( $file->isValid () && !$file->hasMoved () ) {
            $newName = $file->getRandomName ();
            $file->move ( FCPATH . 'public/uploads/community/media', $newName );

            $this->mediaModel->insert ( [
              'post_id'    => $postId,
              'media_type' => $file->getClientMimeType (),
              'media_url'  => base_url ( 'public/uploads/community/media/' . $newName ),
              'created_at' => date ( 'Y-m-d H:i:s' )
            ] );
          }
        }
      }

      // Handle Tags (Hashtags)
      $tags = $this->request->getPost ( 'tags' );

      // If client didn't send tags, try to extract from post_text
      if ( empty ( $tags ) && !empty ( $postText ) ) {
        preg_match_all ( '/#(\w+)/', $postText, $matches );
        if ( !empty ( $matches[1] ) ) {
          $tags = $matches[1];
        }
      }

      if ( !empty ( $tags ) ) {
        if ( is_string ( $tags ) ) $tags = array_map ( 'trim', explode ( ',', $tags ) );
        foreach ( $tags as $tag ) {
          if ( empty ( $tag ) ) continue;
          $this->postTagModel->insert ( [
            'post_id'    => $postId,
            'tag'        => ltrim ( $tag, '#' ),
            'created_at' => date ( 'Y-m-d H:i:s' )
          ] );
        }
      }

      $this->db->transComplete ();
      if ( $this->db->transStatus () === false ) throw new Exception( 'Transaction failed' );

      // Notify Channel Members
      try {
        $channel     = $this->channelModel->find ( $this->request->getPost ( 'channel_id' ) );
        $channelName = $channel['name'] ?? 'Channel';
        $userId      = $this->request->getPost ( 'user_id' );

        // Get members of this channel (excluding the author)
        $members = $this->channelJoinModel->where ( 'channel_id', $this->request->getPost ( 'channel_id' ) )
          ->where ( 'user_id !=', $userId )
          ->findAll ();

        if ( !empty ( $members ) ) {
          $memberIds = array_column ( $members, 'user_id' );
          $tokens    = $this->db->table ( 'users' )
            ->select ( 'device_token' )
            ->whereIn ( 'id', $memberIds )
            ->where ( 'device_token !=', '' )
            ->get ()->getResultArray ();

          $fcmTokens = array_column ( $tokens, 'device_token' );

          if ( !empty ( $fcmTokens ) ) {
            $this->pushNotify->sendMulticast (
              $fcmTokens,
              "New Post in #{$channelName} Channel",
              "A new post was shared in #{$channelName}. Check it out now!",
              'PostDetailScreen',
              ['postId' => $postId]
            );
          }
        }
      } catch (Exception $e) {
        \log_message ( 'error', 'Push Notify Error (New Post): ' . $e->getMessage () );
      }

      return $this->sendResponse ( true, 'Post created successfully', ['post_id' => $postId] );
    } catch (Exception $e) {
      $this->db->transRollback ();
      return $this->sendResponse ( false, 'Error creating post: ' . $e->getMessage () );
    }
  }

  public function updatePost()
  {
    $rules = [
      'post_id'   => 'required|numeric',
      'user_id'   => 'required|numeric',
      'post_text' => 'required|string',
    ];

    if ( !$this->validate ( $rules ) ) {
      return $this->sendResponse ( false, 'Validation failed', $this->validator->getErrors () );
    }

    $postId = $this->request->getPost ( 'post_id' );
    $userId = $this->request->getPost ( 'user_id' );

    $post = $this->postModel->find ( $postId );
    if ( !$post || $post['user_id'] != $userId ) {
      return $this->sendResponse ( false, 'Unauthorized or post not found' );
    }
    // Handle Media Retrieval
    $mediaFiles = [];

    // 1. Try getFileMultiple 
    $multiple = $this->request->getFileMultiple ( 'media' );
    if ( $multiple ) {
      $mediaFiles = $multiple;
    }
    else {
      // 2. Try getFile 
      $single = $this->request->getFile ( 'media' );
      if ( $single ) {
        $mediaFiles = [$single];
      }
    }

    $updated = $this->postModel->update ( $postId, [
      'post_text'  => $this->request->getPost ( 'post_text' ),
      'updated_at' => date ( 'Y-m-d H:i:s' )
    ] );

    if ( $mediaFiles ) {
      foreach ( $mediaFiles as $file ) {
        if ( $file->isValid () && !$file->hasMoved () ) {
          $newName = $file->getRandomName ();
          $file->move ( FCPATH . 'public/uploads/community/media', $newName );

          $this->mediaModel->insert ( [
            'post_id'    => $postId,
            'media_type' => $file->getClientMimeType (),
            'media_url'  => base_url ( 'public/uploads/community/media/' . $newName ),
            'created_at' => date ( 'Y-m-d H:i:s' )
          ] );
        }
      }
    }

    return $updated ? $this->sendResponse ( true, 'Post updated' ) : $this->sendResponse ( false, 'Update failed' );
  }

  public function deletePost()
  {
    $rules = [
      'post_id' => 'required|numeric',
      'user_id' => 'required|numeric',
    ];

    if ( !$this->validate ( $rules ) ) {
      return $this->sendResponse ( false, 'Validation failed', $this->validator->getErrors () );
    }

    $postId = $this->request->getJsonVar ( 'post_id' );
    $userId = $this->request->getJsonVar ( 'user_id' );

    $post = $this->postModel->find ( $postId );
    if ( !$post || $post['user_id'] != $userId ) {
      return $this->sendResponse ( false, 'Unauthorized or post not found' );
    }

    $deleted = $this->postModel->update ( $postId, ['status' => 'deleted'] );
    return $deleted ? $this->sendResponse ( true, 'Post deleted' ) : $this->sendResponse ( false, 'Delete failed' );
  }

  public function getAllPosts()
  {
    $userId = $this->request->getVar ( 'user_id' );
    $page   = (int) ( $this->request->getVar ( 'page' ) ?? 1 );
    $limit  = (int) ( $this->request->getVar ( 'limit' ) ?? 20 );
    $offset = ( $page - 1 ) * $limit;

    $builder = $this->postModel
      ->select ( 'community_posts.*, IF(community_posts.user_type = \'admin\', \'Petsfolio\', COALESCE(users.name, employees.name)) as name, COALESCE(users.profile, employees.profile) as profile' )
      ->join ( 'users', "users.id = community_posts.user_id AND community_posts.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left' );

    $builder->where ( 'community_posts.status', 'active' );
    $totalCount = $builder->countAllResults ( false );

    $posts = $builder->orderBy ( 'community_posts.is_pinned', 'DESC' )
      ->orderBy ( 'community_posts.id', 'DESC' )
      ->limit ( $limit, $offset )
      ->findAll ();

    return $this->sendResponse ( true, 'Posts retrieved', [
      'posts'      => $this->formatPosts ( $posts, $userId ),
      'pagination' => [
        'total_count' => (int) $totalCount,
        'page'        => $page,
        'limit'       => $limit,
        'total_pages' => ceil ( $totalCount / $limit )
      ]
    ] );
  }

  public function getPostByChannel()
  {
    $channelId = $this->request->getVar ( 'channel_id' );
    $userId    = $this->request->getVar ( 'user_id' );
    $page      = (int) ( $this->request->getVar ( 'page' ) ?? 1 );
    $limit     = (int) ( $this->request->getVar ( 'limit' ) ?? 20 );
    $offset    = ( $page - 1 ) * $limit;

    if ( empty ( $channelId ) ) {
      return $this->sendResponse ( false, 'Channel ID is mandatory' );
    }

    $builder = $this->postModel
      ->select ( 'community_posts.*, IF(community_posts.user_type = \'admin\', \'Petsfolio\', COALESCE(users.name, employees.name)) as name, COALESCE(users.profile, employees.profile) as profile' )
      ->join ( 'users', "users.id = community_posts.user_id AND community_posts.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left' )
      ->groupStart ()
      ->where ( 'community_posts.channel_id', $channelId )
      ->orWhere ( "EXISTS (SELECT 1 FROM community_post_channels pc WHERE pc.post_id = community_posts.id AND pc.channel_id = {$channelId})" )
      ->groupEnd ()
      ->where ( 'community_posts.status', 'active' );

    $totalCount = $builder->countAllResults ( false );

    $posts = $builder->orderBy ( 'community_posts.id', 'DESC' )
      ->limit ( $limit, $offset )
      ->findAll ();

    return $this->sendResponse ( true, 'Channel posts retrieved', [
      'posts'      => $this->formatPosts ( $posts, $userId ),
      'pagination' => [
        'total_count' => (int) $totalCount,
        'page'        => $page,
        'limit'       => $limit,
        'total_pages' => ceil ( $totalCount / $limit )
      ]
    ] );
  }
  public function getPostById()
  {
    $postId = $this->request->getVar ( 'post_id' );
    $userId = $this->request->getVar ( 'user_id' );

    if ( empty ( $postId ) ) {
      return $this->sendResponse ( false, 'Post ID is mandatory' );
    }

    $post = $this->postModel
      ->select ( 'community_posts.*, IF(community_posts.user_type = \'admin\', \'Petsfolio\', COALESCE(users.name, employees.name)) as name, COALESCE(users.profile, employees.profile) as profile' )
      ->join ( 'users', "users.id = community_posts.user_id AND community_posts.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left' )
      ->where ( 'community_posts.id', $postId )
      ->where ( 'community_posts.status', 'active' )
      ->first ();

    if ( !$post ) {
      return $this->sendResponse ( false, 'Post not found' );
    }

    // Media
    $post['media'] = $this->mediaModel->where ( 'post_id', $postId )->findAll ();

    // Interaction Status
    $post['is_liked']    = $userId ? ( $this->likeModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->countAllResults () > 0 ) : false;
    $post['is_disliked'] = $userId ? ( $this->postDislikeModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->countAllResults () > 0 ) : false;
    $post['is_saved']    = $userId ? ( $this->saveModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->countAllResults () > 0 ) : false;

    // Counts
    $post['likes_count']    = (int) $this->likeModel->where ( 'post_id', $postId )->countAllResults ();
    $post['dislikes_count'] = (int) $this->postDislikeModel->where ( 'post_id', $postId )->countAllResults ();
    $post['replies_count']  = (int) $this->commentModel->where ( ['post_id' => $postId, 'parent_comment_id !=' => null] )->countAllResults ();
    $post['saves_count']    = (int) $this->saveModel->where ( 'post_id', $postId )->countAllResults ();

    // User Profile URL
    $post['profile_url'] = base_url ( 'public/uploads/users/' . ( $post['profile'] ?? 'default.png' ) );

    // Channel Info
    $post['channel'] = $this->postChannelModel
      ->select ( 'community_channels.*' )
      ->join ( 'community_channels', 'community_channels.id = community_post_channels.channel_id' )
      ->where ( 'post_id', $postId )
      ->first ();

    // Fetch all comments for this post at once to avoid N+1 queries
    $allComments = $this->commentModel
      ->select ( 'community_post_comments.*, IF(community_post_comments.user_type = \'admin\', \'Petsfolio\', COALESCE(users.name, employees.name)) as name, COALESCE(users.profile, employees.profile) as profile' )
      ->join ( 'users', "users.id = community_post_comments.user_id AND community_post_comments.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_post_comments.user_id AND community_post_comments.user_type = 'admin'", 'left' )
      ->where ( 'post_id', $postId )
      ->orderBy ( 'community_post_comments.created_at', 'ASC' )
      ->findAll ();

    $commentMap = [];
    $roots      = [];

    // Pre-process all comments into a map for easy lookup
    foreach ( $allComments as &$c ) {
      $c['likes_count']    = (int) $this->commentLikeModel->where ( 'comment_id', $c['id'] )->countAllResults ();
      $c['dislikes_count'] = (int) $this->commentDislikeModel->where ( 'comment_id', $c['id'] )->countAllResults ();
      $c['is_liked']       = $userId ? ( $this->commentLikeModel->where ( ['comment_id' => $c['id'], 'user_id' => $userId] )->countAllResults () > 0 ) : false;
      $c['is_disliked']    = $userId ? ( $this->commentDislikeModel->where ( ['comment_id' => $c['id'], 'user_id' => $userId] )->countAllResults () > 0 ) : false;
      $c['profile_url']    = base_url ( 'public/uploads/users/' . ( $c['profile'] ?? 'default.png' ) );
      $c['reply_count']    = 0;
      $c['replies']        = [];
      $c['reply_to_user']  = null;
      unset ($c['profile']);
      $commentMap[$c['id']] = &$c;
    }

    // Build the tree hierarchy
    foreach ( $allComments as &$c ) {
      if ( $c['parent_comment_id'] && isset ( $commentMap[$c['parent_comment_id']] ) ) {
        // Set who we're replying to (parent's author name)
        $c['reply_to_user'] = $commentMap[$c['parent_comment_id']]['name'];
        // Nest inside parent
        $commentMap[$c['parent_comment_id']]['replies'][] = &$c;
        $commentMap[$c['parent_comment_id']]['reply_count']++;
      }
      else {
        // Top-level comment
        $roots[] = &$c;
      }
    }

    // Newest top-level comments first
    $dataComments = array_reverse ( $roots );

    $post['comments'] = $dataComments;

    return $this->sendResponse ( true, 'Post details retrieved', $post );
  }

  public function getAllChannels()
  {
    $userId   = $this->request->getVar ( 'user_id' );
    $channels = $this->channelModel->where ( 'status', 1 )->findAll ();

    foreach ( $channels as &$channel ) {
      $channel['icon_url']     = base_url ( 'admin/public/uploads/community/icons/' . $channel['icon'] );
      $channel['is_joined']    = $userId ? ( $this->channelJoinModel->where ( ['user_id' => $userId, 'channel_id' => $channel['id']] )->countAllResults () > 0 ) : false;
      $channel['post_count']   = $this->postChannelModel->where ( ['channel_id' => $channel['id']] )->countAllResults ();
      $channel['member_count'] = $this->channelJoinModel->where ( ['channel_id' => $channel['id']] )->countAllResults ();
    }

    return $this->sendResponse ( true, 'Channels retrieved', $channels );
  }

  public function joinChannel()
  {
    $rules = [
      'user_id'    => 'required|numeric',
      'channel_id' => 'required|numeric'
    ];

    $userId    = $this->request->getVar ( 'user_id' );
    $channelId = $this->request->getVar ( 'channel_id' );

    if ( !$this->validate ( $rules ) ) {
      return $this->sendResponse ( false, 'Validation error', $this->validator->getErrors () );
    }

    $existing = $this->channelJoinModel->where ( ['user_id' => $userId, 'channel_id' => $channelId] )->first ();

    if ( $existing ) {
      $this->channelJoinModel->delete ( $existing['id'] );
      return $this->sendResponse ( true, 'Left channel', ['is_joined' => false] );
    }
    else {
      $this->channelJoinModel->insert ( [
        'user_id'    => $userId,
        'channel_id' => $channelId,
        'created_at' => date ( 'Y-m-d H:i:s' )
      ] );

      // Push Notification for Join Success
      try {
        $channel     = $this->channelModel->find ( $channelId );
        $channelName = $channel['name'] ?? 'Channel';
        $token       = $this->getUserDeviceToken ( $userId );
        if ( $token ) {
          $this->pushNotify->notify (
            $token,
            "Joined Successfully ✅",
            "You’ve joined #{$channelName}. Start exploring posts now!",
            'ChannelsScreen',
            ['channelId' => $channelId]
          );
        }
      } catch (Exception $e) {
        \log_message ( 'error', 'Push Notify Error (Join): ' . $e->getMessage () );
      }

      return $this->sendResponse ( true, 'Joined channel', ['is_joined' => true] );
    }
  }

  public function LikePost()
  {
    $rules = [
      'post_id' => 'required|numeric',
      'user_id' => 'required|numeric'
    ];
    if ( !$this->validate ( $rules ) ) {
      return $this->sendResponse ( false, 'Validation failed', $this->validator->getErrors () );
    }

    $postId = $this->request->getVar ( 'post_id' );
    $userId = $this->request->getVar ( 'user_id' );

    $this->db->transStart ();
    try {
      $existingLike    = $this->likeModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->first ();
      $existingDislike = $this->postDislikeModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->first ();
      $post            = $this->postModel->find ( $postId );
      if ( !$post ) throw new Exception( 'Post not found' );

      if ( $existingLike ) {
        $this->likeModel->delete ( $existingLike['id'] );
        $this->postModel->update ( $postId, ['likes_count' => max ( 0, $post['likes_count'] - 1 )] );
        $status = ['is_liked' => false, 'message' => 'Unliked'];
      }
      else {
        // If disliked, remove dislike
        if ( $existingDislike ) {
          $this->postDislikeModel->delete ( $existingDislike['id'] );
          $this->postModel->update ( $postId, ['dislikes_count' => max ( 0, ( $post['dislikes_count'] ?? 0 ) - 1 )] );
          $post = $this->postModel->find ( $postId );
        }

        $this->likeModel->insert ( [
          'post_id'    => $postId,
          'user_id'    => $userId,
          'created_at' => date ( 'Y-m-d H:i:s' )
        ] );
        $this->postModel->update ( $postId, ['likes_count' => $post['likes_count'] + 1] );

        if ( $post['user_id'] != $userId ) {
          $this->notificationModel->insert ( [
            'user_id'      => $post['user_id'],
            'actor_id'     => $userId,
            'type'         => 'like',
            'reference_id' => $postId,
            'created_at'   => date ( 'Y-m-d H:i:s' )
          ] );

          // Push Notification for Like
          try {
            $token = $this->getUserDeviceToken ( $post['user_id'], $post['user_type'] );
            if ( $token ) {
              $channel     = $this->channelModel->find ( $post['channel_id'] );
              $channelName = $channel['name'] ?? 'Channel';
              $this->pushNotify->notify (
                $token,
                "New Like ❤️",
                "Your post is getting love! Someone liked your post in #{$channelName}.",
                'PostDetailScreen',
                ['postId' => $postId]
              );
            }
          } catch (Exception $e) {
            \log_message ( 'error', 'Push Notify Error (Like): ' . $e->getMessage () );
          }
        }
        $status = ['is_liked' => true, 'message' => 'Liked'];
      }

      $this->db->transComplete ();
      return $this->sendResponse ( true, $status['message'], ['is_liked' => $status['is_liked']] );
    } catch (Exception $e) {
      $this->db->transRollback ();
      return $this->sendResponse ( false, $e->getMessage () );
    }
  }

  public function DislikePost()
  {
    $rules = [
      'post_id' => 'required|numeric',
      'user_id' => 'required|numeric'
    ];
    if ( !$this->validate ( $rules ) ) {
      return $this->sendResponse ( false, 'Validation failed', $this->validator->getErrors () );
    }

    $postId = $this->request->getVar ( 'post_id' );
    $userId = $this->request->getVar ( 'user_id' );

    $this->db->transStart ();
    try {
      $existingDislike = $this->postDislikeModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->first ();
      $existingLike    = $this->likeModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->first ();
      $post            = $this->postModel->find ( $postId );
      if ( !$post ) throw new Exception( 'Post not found' );

      if ( $existingDislike ) {
        $this->postDislikeModel->delete ( $existingDislike['id'] );
        $this->postModel->update ( $postId, ['dislikes_count' => max ( 0, ( $post['dislikes_count'] ?? 0 ) - 1 )] );
        $status = ['is_disliked' => false, 'message' => 'Removed dislike'];
      }
      else {
        // If liked, remove like
        if ( $existingLike ) {
          $this->likeModel->delete ( $existingLike['id'] );
          $this->postModel->update ( $postId, ['likes_count' => max ( 0, $post['likes_count'] - 1 )] );
          $post = $this->postModel->find ( $postId );
        }

        $this->postDislikeModel->insert ( [
          'post_id'    => $postId,
          'user_id'    => $userId,
          'created_at' => date ( 'Y-m-d H:i:s' )
        ] );
        $this->postModel->update ( $postId, ['dislikes_count' => ( $post['dislikes_count'] ?? 0 ) + 1] );
        $status = ['is_disliked' => true, 'message' => 'Disliked'];
      }

      $this->db->transComplete ();
      return $this->sendResponse ( true, $status['message'], ['is_disliked' => $status['is_disliked']] );
    } catch (Exception $e) {
      $this->db->transRollback ();
      return $this->sendResponse ( false, $e->getMessage () );
    }
  }

  public function addComment()
  {
    $rules = [
      'post_id' => 'required|numeric',
      'user_id' => 'required|numeric',
      'comment' => 'required|string',
    ];

    if ( !$this->validate ( $rules ) ) {
      return $this->sendResponse ( false, 'Validation failed', $this->validator->getErrors () );
    }

    $postId   = $this->request->getJsonVar ( 'post_id' );
    $userId   = $this->request->getJsonVar ( 'user_id' );
    $parentId = $this->request->getJsonVar ( 'parent_comment_id' );

    $this->db->transStart ();
    try {
      $commentId = $this->commentModel->insert ( [
        'post_id'           => $postId,
        'user_id'           => $userId,
        'user_type'         => 'user',
        'parent_comment_id' => $parentId ?: null,
        'comment'           => $this->request->getJsonVar ( 'comment' ),
        'created_at'        => date ( 'Y-m-d H:i:s' )
      ] );

      $post = $this->postModel->where ( ['id' => $postId] )->first ();

      if ( !$post ) {
        return $this->sendResponse ( false, 'Post not found' );
      }
      $this->postModel
        ->set ( 'comments_count', 'comments_count + 1', false )
        ->where ( 'id', $postId )
        ->update ();

      if ( $post['user_id'] != $userId ) {
        $this->notificationModel->insert ( [
          'user_id'      => $post['user_id'],
          'actor_id'     => $userId,
          'type'         => 'comment',
          'reference_id' => $postId,
          'created_at'   => date ( 'Y-m-d H:i:s' )
        ] );

        // Push Notification for New Comment
        try {
          $token = $this->getUserDeviceToken ( $post['user_id'], $post['user_type'] );
          if ( $token ) {
            $channel     = $this->channelModel->find ( $post['channel_id'] );
            $channelName = $channel['name'] ?? 'Channel';
            $this->pushNotify->notify (
              $token,
              "New Comment 💬",
              "Someone commented on your post in #{$channelName}. Tap to view and reply.",
              'PostDetailScreen',
              ['postId' => $postId]
            );
          }
        } catch (Exception $e) {
          log_message ( 'error', 'Push Notify Error (Comment): ' . $e->getMessage () );
        }
      }

      if ( $parentId ) {
        $parent = $this->commentModel->find ( $parentId );
        if ( $parent && $parent['user_id'] != $userId ) {
          $this->notificationModel->insert ( [
            'user_id'      => $parent['user_id'],
            'actor_id'     => $userId,
            'type'         => 'reply',
            'reference_id' => $postId,
            'created_at'   => date ( 'Y-m-d H:i:s' )
          ] );

          // Push Notification for New Reply
          try {
            $token = $this->getUserDeviceToken ( $parent['user_id'], 'user' ); // Parent must be a user since only users comment? Actually comments can be from admins too. Need to handle parent user_type if it existed. 
            // Comment model doesn't seem to store user_type. Assuming user for now.
            if ( $token ) {
              $this->pushNotify->notify (
                $token,
                "New Reply 🔁",
                "You’ve got a reply to your comment. Join the conversation now!",
                'PostDetailScreen',
                ['postId' => $postId]
              );
            }
          } catch (Exception $e) {
            \log_message ( 'error', 'Push Notify Error (Reply): ' . $e->getMessage () );
          }
        }
      }

      $this->db->transComplete ();
      return $this->sendResponse ( true, 'Comment added', ['comment_id' => $commentId] );
    } catch (Exception $e) {
      $this->db->transRollback ();
      return $this->sendResponse ( false, $e->getMessage () );
    }
  }

  public function deleteComment()
  {
    $commentId = $this->request->getJsonVar ( 'comment_id' );
    $userId    = $this->request->getJsonVar ( 'user_id' );

    $this->db->transStart ();
    try {
      $comment = $this->commentModel->find ( $commentId );
      if ( !$comment || $comment['user_id'] != $userId ) {
        throw new Exception( 'Unauthorized or comment not found' );
      }

      $this->commentModel->delete ( $commentId );
      $post = $this->postModel->find ( $comment['post_id'] );
      if ( $post ) {
        $this->postModel->update ( $post['id'], ['comments_count' => max ( 0, $post['comments_count'] - 1 )] );
      }

      $this->db->transComplete ();
      return $this->sendResponse ( true, 'Comment deleted' );
    } catch (Exception $e) {
      $this->db->transRollback ();
      return $this->sendResponse ( false, $e->getMessage () );
    }
  }

  public function likeAndDislikeForAComment()
  {
    $commentId = $this->request->getJsonVar ( 'comment_id' );
    $userId    = $this->request->getJsonVar ( 'user_id' );

    $this->db->transStart ();
    try {
      $comment = $this->commentModel->find ( $commentId );
      if ( !$comment ) throw new Exception( 'Comment not found' );

      $existingLike    = $this->commentLikeModel->where ( ['comment_id' => $commentId, 'user_id' => $userId] )->first ();
      $existingDislike = $this->commentDislikeModel->where ( ['comment_id' => $commentId, 'user_id' => $userId] )->first ();

      if ( $existingLike ) {
        $this->commentLikeModel->where ( ['comment_id' => $commentId, 'user_id' => $userId] )->delete ();
        $this->commentModel->update ( $commentId, ['likes_count' => max ( 0, $comment['likes_count'] - 1 )] );
        $res = ['is_liked' => false, 'message' => 'Unliked comment'];
      }
      else {
        // If disliked, remove dislike
        if ( $existingDislike ) {
          $this->commentDislikeModel->where ( ['comment_id' => $commentId, 'user_id' => $userId] )->delete ();
          $this->commentModel->update ( $commentId, ['dislikes_count' => max ( 0, ( $comment['dislikes_count'] ?? 0 ) - 1 )] );
          $comment = $this->commentModel->find ( $commentId );
        }

        $this->commentLikeModel->insert ( [
          'comment_id' => $commentId,
          'user_id'    => $userId,
          'created_at' => date ( 'Y-m-d H:i:s' )
        ] );
        $this->commentModel->update ( $commentId, ['likes_count' => $comment['likes_count'] + 1] );
        $res = ['is_liked' => true, 'message' => 'Liked comment'];
      }

      $this->db->transComplete ();
      return $this->sendResponse ( true, $res['message'], ['is_liked' => $res['is_liked']] );
    } catch (Exception $e) {
      $this->db->transRollback ();
      return $this->sendResponse ( false, $e->getMessage () );
    }
  }

  public function DislikeComment()
  {
    $commentId = $this->request->getJsonVar ( 'comment_id' );
    $userId    = $this->request->getJsonVar ( 'user_id' );

    $this->db->transStart ();
    try {
      $comment = $this->commentModel->find ( $commentId );
      if ( !$comment ) throw new Exception( 'Comment not found' );

      $existingDislike = $this->commentDislikeModel->where ( ['comment_id' => $commentId, 'user_id' => $userId] )->first ();
      $existingLike    = $this->commentLikeModel->where ( ['comment_id' => $commentId, 'user_id' => $userId] )->first ();

      if ( $existingDislike ) {
        $this->commentDislikeModel->where ( ['comment_id' => $commentId, 'user_id' => $userId] )->delete ();
        $this->commentModel->update ( $commentId, ['dislikes_count' => max ( 0, ( $comment['dislikes_count'] ?? 0 ) - 1 )] );
        $res = ['is_disliked' => false, 'message' => 'Removed dislike from comment'];
      }
      else {
        // If liked, remove like
        if ( $existingLike ) {
          $this->commentLikeModel->where ( ['comment_id' => $commentId, 'user_id' => $userId] )->delete ();
          $this->commentModel->update ( $commentId, ['likes_count' => max ( 0, $comment['likes_count'] - 1 )] );
          $comment = $this->commentModel->find ( $commentId );
        }

        $this->commentDislikeModel->insert ( [
          'comment_id' => $commentId,
          'user_id'    => $userId,
          'created_at' => date ( 'Y-m-d H:i:s' )
        ] );
        $this->commentModel->update ( $commentId, ['dislikes_count' => ( $comment['dislikes_count'] ?? 0 ) + 1] );
        $res = ['is_disliked' => true, 'message' => 'Disliked comment'];
      }

      $this->db->transComplete ();
      return $this->sendResponse ( true, $res['message'], ['is_disliked' => $res['is_disliked']] );
    } catch (Exception $e) {
      $this->db->transRollback ();
      return $this->sendResponse ( false, $e->getMessage () );
    }
  }

  public function getMyPosts()
  {
    $userId = $this->request->getVar ( 'user_id' );
    $page   = (int) ( $this->request->getVar ( 'page' ) ?? 1 );
    $limit  = (int) ( $this->request->getVar ( 'limit' ) ?? 20 );
    $offset = ( $page - 1 ) * $limit;

    if ( !$userId ) return $this->sendResponse ( false, 'User ID required' );

    $builder = $this->postModel
      ->select ( 'community_posts.*, IF(community_posts.user_type = \'admin\', \'Petsfolio\', COALESCE(users.name, employees.name)) as name, COALESCE(users.profile, employees.profile) as profile' )
      ->join ( 'users', "users.id = community_posts.user_id AND community_posts.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left' )
      ->where ( 'community_posts.user_id', $userId )
      ->whereIn ( 'community_posts.status', ['active', 'blocked'] );

    $totalCount = $builder->countAllResults ( false );

    $posts = $builder->orderBy ( 'id', 'DESC' )
      ->limit ( $limit, $offset )
      ->findAll ();

    return $this->sendResponse ( true, 'Your posts retrieved', [
      'posts'      => $this->formatPosts ( $posts, $userId ),
      'pagination' => [
        'total_count' => (int) $totalCount,
        'page'        => $page,
        'limit'       => $limit,
        'total_pages' => ceil ( $totalCount / $limit )
      ]
    ] );
  }

  public function getMyComments()
  {
    $userId = $this->request->getVar ( 'user_id' );
    $page   = (int) ( $this->request->getVar ( 'page' ) ?? 1 );
    $limit  = (int) ( $this->request->getVar ( 'limit' ) ?? 20 );
    $offset = ( $page - 1 ) * $limit;

    if ( !$userId ) return $this->sendResponse ( false, 'User ID required' );

    $builder = $this->commentModel
      ->select ( 'community_post_comments.*, IF(community_posts.user_type = \'admin\', \'Petsfolio\', COALESCE(users.name, employees.name)) as name, COALESCE(users.profile, employees.profile) as profile' )
      ->select ( 'community_channels.name as channel_name, community_channels.handle as handle' )
      ->select ( 'community_posts.post_text as post_preview' )
      ->select ( 'community_posts.id as post_id' )


      ->join ( 'community_posts', 'community_posts.id = community_post_comments.post_id' )
      ->join ( 'users', "users.id = community_posts.user_id AND community_posts.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left' )
      ->join ( 'community_channels', 'community_channels.id = community_posts.channel_id', 'left' )
      ->where ( 'community_post_comments.user_id', $userId );

    $totalCount = $builder->countAllResults ( false );

    $comments = $builder->orderBy ( 'community_post_comments.id', 'DESC' )
      ->limit ( $limit, $offset )
      ->findAll ();

    foreach ( $comments as &$c ) {
      $c['profile_url'] = base_url ( 'public/uploads/users/' . ( $c['profile'] ?? 'default.png' ) );
      unset ($c['profile']);
    }

    return $this->sendResponse ( true, 'Your comments retrieved', [
      'comments'   => $comments,
      'pagination' => [
        'total_count' => (int) $totalCount,
        'page'        => $page,
        'limit'       => $limit,
        'total_pages' => ceil ( $totalCount / $limit )
      ]
    ] );
  }

  public function saveMyPost()
  {
    $postId = $this->request->getVar ( 'post_id' );
    $userId = $this->request->getVar ( 'user_id' );

    if ( !$postId || !$userId ) return $this->sendResponse ( false, 'Post ID and User ID required' );

    $existing = $this->saveModel->where ( ['post_id' => $postId, 'user_id' => $userId] )->first ();
    if ( $existing ) {
      $this->saveModel->delete ( $existing['id'] );
      return $this->sendResponse ( true, 'Removed from saved', ['is_saved' => false] );
    }
    else {
      $this->saveModel->insert ( [
        'post_id'    => $postId,
        'user_id'    => $userId,
        'created_at' => date ( 'Y-m-d H:i:s' )
      ] );
      return $this->sendResponse ( true, 'Post saved', ['is_saved' => true] );
    }
  }

  public function getMySavedPosts()
  {
    $userId = $this->request->getVar ( 'user_id' );
    $page   = (int) ( $this->request->getVar ( 'page' ) ?? 1 );
    $limit  = (int) ( $this->request->getVar ( 'limit' ) ?? 20 );
    $offset = ( $page - 1 ) * $limit;

    if ( !$userId ) return $this->sendResponse ( false, 'User ID required' );

    $builder = $this->postModel
      ->select ( 'community_posts.*, IF(community_posts.user_type = \'admin\', \'Petsfolio\', COALESCE(users.name, employees.name)) as name, COALESCE(users.profile, employees.profile) as profile' )
      ->join ( 'users', "users.id = community_posts.user_id AND community_posts.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left' )
      ->join ( 'community_post_saves', 'community_post_saves.post_id = community_posts.id' )
      ->where ( 'community_post_saves.user_id', $userId )
      ->where ( 'community_posts.status', 'active' );

    $totalCount = $builder->countAllResults ( false );

    $posts = $builder->orderBy ( 'community_posts.id', 'DESC' )
      ->limit ( $limit, $offset )
      ->findAll ();

    return $this->sendResponse ( true, 'Saved posts retrieved', [
      'posts'      => $this->formatPosts ( $posts, $userId ),
      'pagination' => [
        'total_count' => (int) $totalCount,
        'page'        => $page,
        'limit'       => $limit,
        'total_pages' => ceil ( $totalCount / $limit )
      ]
    ] );
  }

  public function ReportPost()
  {
    $rules = [
      'post_id' => 'required|numeric',
      'user_id' => 'required|numeric',
      'title'   => 'required|string',
    ];

    if ( !$this->validate ( $rules ) ) {
      return $this->sendResponse ( false, 'Validation failed', $this->validator->getErrors () );
    }

    $this->reportModel->insert ( [
      'post_id'    => $this->request->getJsonVar ( 'post_id' ),
      'user_id'    => $this->request->getJsonVar ( 'user_id' ),
      'user_type'  => 'user',
      'title'      => $this->request->getJsonVar ( 'title' ),
      'reason'     => $this->request->getJsonVar ( 'reason' ) ?? '',
      'created_at' => date ( 'Y-m-d H:i:s' )
    ] );

    return $this->sendResponse ( true, 'Report submitted successfully' );
  }

  public function ReportComment()
  {
    $rules = [
      'comment_id' => 'required|numeric',
      'user_id'    => 'required|numeric',
      'reason'     => 'required|string',
    ];

    if ( !$this->validate ( $rules ) ) {
      return $this->sendResponse ( false, 'Validation failed', $this->validator->getErrors () );
    }

    $comment = $this->commentModel->find ( $this->request->getJsonVar ( 'comment_id' ) );
    if ( !$comment ) return $this->sendResponse ( false, 'Comment not found' );

    $this->reportModel->insert ( [
      'post_id'     => $comment['post_id'],
      'comment_id'  => $this->request->getJsonVar ( 'comment_id' ),
      'user_id'     => $this->request->getJsonVar ( 'user_id' ),
      'reason'      => $this->request->getJsonVar ( 'reason' ),
      'description' => $this->request->getJsonVar ( 'description' ),
      'created_at'  => date ( 'Y-m-d H:i:s' )
    ] );

    return $this->sendResponse ( true, 'Comment report submitted successfully' );
  }

  public function getTrendingFeed()
  {
    $userId = $this->request->getVar ( 'user_id' );
    $page   = (int) ( $this->request->getVar ( 'page' ) ?? 1 );
    $limit  = (int) ( $this->request->getVar ( 'limit' ) ?? 10 );
    $offset = ( $page - 1 ) * $limit;

    $builder = $this->postModel
      ->select ( 'community_posts.*, IF(community_posts.user_type = \'admin\', \'Petsfolio\', COALESCE(users.name, employees.name)) as name, COALESCE(users.profile, employees.profile) as profile' )
      ->join ( 'users', "users.id = community_posts.user_id AND community_posts.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left' )
      ->where ( 'community_posts.status', 'active' );

    $totalCount = $builder->countAllResults ( false );

    $posts = $builder->orderBy ( 'community_posts.likes_count', 'DESC' )
      ->limit ( $limit, $offset )
      ->findAll ();

    return $this->sendResponse ( true, 'Trending feed retrieved', [
      'posts'      => $this->formatPosts ( $posts, $userId ),
      'pagination' => [
        'total_count' => (int) $totalCount,
        'page'        => $page,
        'limit'       => $limit,
        'total_pages' => ceil ( $totalCount / $limit )
      ]
    ] );
  }

  /**
   * Helper to get user device token
   */
  private function getUserDeviceToken( $userId, $userType = 'user' )
  {
    if ( $userType == 'admin' ) {
      $row = '';
    }
    else {
      $row = $this->db->table ( 'users' )->select ( 'device_token' )->where ( 'id', $userId )->get ()->getRow ();
    }
    return $row ? $row->device_token : null;
  }

  public function deleteMedia()
  {
    $mediaId = $this->request->getJsonVar ( 'media_id' );
    $media   = $this->mediaModel->find ( $mediaId );
    if ( !$media ) return $this->sendResponse ( false, 'Media not found' );
    $this->mediaModel->delete ( $mediaId );
    return $this->sendResponse ( true, 'Media deleted successfully' );
  }

  public function search()
  {
    $keyword = $this->request->getVar ( 'keyword' );
    $userId  = $this->request->getVar ( 'user_id' );
    $page    = (int) ( $this->request->getVar ( 'page' ) ?? 1 );
    $limit   = (int) ( $this->request->getVar ( 'limit' ) ?? 10 );
    $offset  = ( $page - 1 ) * $limit;

    $builder = $this->postModel
      ->select ( 'community_posts.*, IF(community_posts.user_type = \'admin\', \'Petsfolio\', COALESCE(users.name, employees.name)) as name, COALESCE(users.profile, employees.profile) as profile' )
      ->join ( 'users', "users.id = community_posts.user_id AND community_posts.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left' )
      ->where ( 'community_posts.status', 'active' )
      ->like ( 'community_posts.post_text', $keyword );

    $totalCount = $builder->countAllResults ( false );

    $posts = $builder->orderBy ( 'community_posts.id', 'DESC' )
      ->limit ( $limit, $offset )
      ->findAll ();

    return $this->sendResponse ( true, 'Search results retrieved', [
      'posts'      => $this->formatPosts ( $posts, $userId ),
      'pagination' => [
        'total_count' => (int) $totalCount,
        'page'        => $page,
        'limit'       => $limit,
        'total_pages' => ceil ( $totalCount / $limit )
      ]
    ] );
  }

  public function adminGetDashboardStats()
  {
    $totalUsers     = $this->db->table ( 'users' )->countAllResults ();
    $totalPosts     = $this->postModel->where ( 'status !=', 'deleted' )->countAllResults ();
    $totalComments  = $this->commentModel->countAllResults ();
    $pendingReports = $this->reportModel->join ( 'community_posts', 'community_posts.id = community_post_reports.post_id' )
      ->where ( 'community_posts.status', 'active' )
      ->countAllResults ();

    $engagement = $this->postModel->select ( 'SUM(likes_count) as total_likes, SUM(comments_count) as total_comments' )->first ();
    $totalEngagement = ( (int) ( $engagement['total_likes'] ?? 0 ) ) + ( (int) ( $engagement['total_comments'] ?? 0 ) );

    return $this->sendResponse ( true, 'Dashboard stats retrieved', [
      'total_users'      => $totalUsers,
      'total_posts'      => $totalPosts,
      'total_comments'   => $totalComments,
      'pending_reports'  => $pendingReports,
      'total_engagement' => $totalEngagement
    ] );
  }

  public function adminAddPost()
  {
    $postText   = $this->request->getPost ( 'post_text' );
    $channelId  = $this->request->getPost ( 'channel_id' );
    $adminId    = $this->request->getPost ( 'admin_id' ) ?? 1; // Default to system admin
    $mediaFiles = $this->request->getFileMultiple ( 'media' ) ?? [];

    $postData = [
      'user_id'    => $adminId,
      'user_type'  => 'admin', // Critical for frontend display as "Petsfolio"
      'post_text'  => $postText,
      'channel_id' => $channelId,
      'status'     => 'active',
      'post_type'  => count ( $mediaFiles ) > 0 ? 'image' : 'text'
    ];

    if ( !$this->postModel->insert ( $postData ) ) {
      return $this->sendResponse ( false, 'Failed to create post', $this->postModel->errors () );
    }

    $postId = $this->postModel->getInsertID ();

    // Handle Media
    foreach ( $mediaFiles as $file ) {
      if ( $file->isValid () && !$file->hasMoved () ) {
        $newName = $file->getRandomName ();
        $file->move ( ROOTPATH . 'public/uploads/community/', $newName );

        $this->mediaModel->insert ( [
          'post_id'    => $postId,
          'media_url'  => $newName,
          'media_type' => 'image'
        ] );
      }
    }

    return $this->sendResponse ( true, 'Post created successfully', ['id' => $postId] );
  }

  // --- ADMIN API FUNCTIONS ---

  public function adminGetChannels()
  {
    $search = $this->request->getVar ( 'search' );
    $query  = $this->channelModel;
    if ( $search ) {
      $query->groupStart ()
        ->like ( 'name', $search )
        ->orLike ( 'description', $search )
        ->orLike ( 'handle', $search )
        ->groupEnd ();
    }

    $channels = $query->findAll ();
    foreach ( $channels as &$channel ) {
      $channel['member_count'] = $this->channelJoinModel->where ( 'channel_id', $channel['id'] )->countAllResults ();
      $channel['post_count']   = $this->postChannelModel->where ( 'channel_id', $channel['id'] )->countAllResults ();
      $channel['icon_url']     = base_url ( 'admin/public/uploads/community/icons/' . $channel['icon'] );
    }

    $posts            = $this->postModel->select ( 'SUM(likes_count) as total_likes, SUM(comments_count) as total_comments' )->first ();
    $total_engagement = ( (int) ( $posts['total_likes'] ?? 0 ) ) + ( (int) ( $posts['total_comments'] ?? 0 ) );

    return $this->sendResponse ( true, 'Channels retrieved', [
      'channels'         => $channels,
      'total_engagement' => $total_engagement
    ] );
  }

  public function adminAddChannel()
  {
    $data = [
      'name'        => $this->request->getPost ( 'name' ),
      'handle'      => $this->request->getPost ( 'handle' ),
      'description' => $this->request->getPost ( 'description' ),
      'status'      => $this->request->getPost ( 'status' ) ? 1 : 0,
      'is_private'  => $this->request->getPost ( 'is_private' ) ? 1 : 0,
    ];

    $img = $this->request->getFile ( 'icon' );
    if ( $img && $img->isValid () && !$img->hasMoved () ) {
      $newName = $img->getRandomName ();
      $img->move ( FCPATH . 'public/uploads/community/icons', $newName );
      $data['icon'] = $newName;
    }
    else {
      $data['icon'] = 'default_icon.png';
    }

    $id = $this->channelModel->insert ( $data );
    return $this->sendResponse ( true, 'Channel added successfully', ['id' => $id] );
  }

  public function adminUpdateChannel()
  {
    $id   = $this->request->getPost ( 'id' );
    $data = [
      'name'        => $this->request->getPost ( 'name' ),
      'handle'      => $this->request->getPost ( 'handle' ),
      'description' => $this->request->getPost ( 'description' ),
      'status'      => $this->request->getPost ( 'status' ) ? 1 : 0,
      'is_private'  => $this->request->getPost ( 'is_private' ) ? 1 : 0,
    ];

    $img = $this->request->getFile ( 'icon' );
    if ( $img && $img->isValid () && !$img->hasMoved () ) {
      $newName = $img->getRandomName ();
      $img->move ( FCPATH . 'public/uploads/community/icons', $newName );
      $data['icon'] = $newName;
    }

    $this->channelModel->update ( $id, $data );
    return $this->sendResponse ( true, 'Channel updated successfully' );
  }

  public function adminDeleteChannel()
  {
    $id = $this->request->getJsonVar ( 'id' ) ?? $this->request->getVar ( 'id' );
    $this->channelModel->delete ( $id );
    return $this->sendResponse ( true, 'Channel deleted' );
  }

  public function adminGetPosts()
  {
    $status    = $this->request->getVar ( 'status' ) ?? 'admin';
    $search    = $this->request->getVar ( 'search' );
    $channelId = $this->request->getVar ( 'channel_id' );

    $query = $this->postModel
      ->select ( 'community_posts.*, COALESCE(users.name, employees.name) as name, community_channels.name as channel_name' )
      ->join ( 'users', "users.id = community_posts.user_id AND community_posts.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left' )
      ->join ( 'community_channels', 'community_channels.id = community_posts.channel_id', 'left' )
      ->where ( 'community_posts.status !=', 'deleted' );

    if ( $status == 'admin' ) {
      $query->where ( 'community_posts.user_type', 'admin' )->where ( 'community_posts.status', 'active' );
    }
    elseif ( $status == 'scheduled' ) {
      $query->where ( 'community_posts.status', 'scheduled' );
    }
    elseif ( $status == 'user' ) {
      $query->where ( 'community_posts.user_type !=', 'admin' )->where ( 'community_posts.status', 'active' );
    }
    elseif ( $status == 'blocked' ) {
      $query->where ( 'community_posts.status', 'blocked' );
    }

    if ( $search ) {
      $query->groupStart ()
        ->like ( 'community_posts.post_text', $search )
        ->orLike ( 'users.name', $search )
        ->orLike ( 'employees.name', $search )
        ->groupEnd ();
    }

    if ( $channelId ) {
      $query->where ( 'community_posts.channel_id', $channelId );
    }

    $posts = $query->orderBy ( 'community_posts.id', 'DESC' )->findAll ();

    foreach ( $posts as &$post ) {
      $post['media']          = $this->mediaModel->where ( 'post_id', $post['id'] )->findAll ();
      $post['likes_count']    = (int) $this->likeModel->where ( 'post_id', $post['id'] )->countAllResults ();
      $post['comments_count'] = (int) $this->commentModel->where ( 'post_id', $post['id'] )->countAllResults ();
    }

    return $this->sendResponse ( true, 'Admin posts retrieved', $posts );
  }

  public function adminApprovePost()
  {
    $id = $this->request->getJsonVar ( 'id' ) ?? $this->request->getVar ( 'id' );
    $this->postModel->update ( $id, ['status' => 'active'] );
    return $this->sendResponse ( true, 'Post approved' );
  }

  public function adminTogglePin()
  {
    $id   = $this->request->getJsonVar ( 'id' ) ?? $this->request->getVar ( 'id' );
    $post = $this->postModel->find ( $id );
    if ( !$post ) return $this->sendResponse ( false, 'Post not found' );

    $newStatus = $post['is_pinned'] ? 0 : 1;
    if ( $newStatus == 1 ) {
      $this->postModel->where ( 'is_pinned', 1 )->set ( ['is_pinned' => 0] )->update ();
    }

    $this->postModel->update ( $id, ['is_pinned' => $newStatus] );
    return $this->sendResponse ( true, 'Post pin status updated', ['is_pinned' => $newStatus] );
  }

  public function adminDeletePost()
  {
    $id = $this->request->getJsonVar ( 'id' ) ?? $this->request->getVar ( 'id' );
    $this->postModel->update ( $id, ['status' => 'deleted'] );
    return $this->sendResponse ( true, 'Post deleted' );
  }

  public function adminBlockPost()
  {
    $id = $this->request->getJsonVar ( 'id' ) ?? $this->request->getVar ( 'id' );
    $this->postModel->update ( $id, ['status' => 'blocked'] );
    return $this->sendResponse ( true, 'Post blocked' );
  }

  public function adminUnblockPost()
  {
    $id = $this->request->getJsonVar ( 'id' ) ?? $this->request->getVar ( 'id' );
    $this->postModel->update ( $id, ['status' => 'active'] );
    return $this->sendResponse ( true, 'Post unblocked' );
  }

  public function adminGetReports()
  {
    $status = $this->request->getVar ( 'status' ) ?? 'all';
    $search = $this->request->getVar ( 'search' );

    $query = $this->reportModel
      ->select ( 'community_post_reports.*, community_posts.post_text, community_posts.status as post_status, COALESCE(users.name, employees.name) as name, community_channels.name as channel_name' )
      ->join ( 'community_posts', 'community_posts.id = community_post_reports.post_id', 'left' )
      ->join ( 'users', "users.id = community_post_reports.user_id AND community_post_reports.user_type != 'admin'", 'left' )
      ->join ( 'employees', "employees.id = community_post_reports.user_id AND community_post_reports.user_type = 'admin'", 'left' )
      ->join ( 'community_channels', 'community_channels.id = community_posts.channel_id', 'left' );

    if ( $status == 'pending' ) {
      $query->where ( 'community_posts.status', 'active' );
    }
    elseif ( $status == 'resolved' ) {
      $query->where ( 'community_posts.status', 'blocked' );
    }

    $postId = $this->request->getVar ( 'post_id' );
    if ( $postId ) {
      $query->where ( 'community_post_reports.post_id', $postId );
    }

    if ( $search ) {
      $query->groupStart ()
        ->like ( 'community_posts.post_text', $search )
        ->orLike ( 'users.name', $search )
        ->orLike ( 'community_post_reports.reason', $search )
        ->groupEnd ();
    }

    $reports = $query->orderBy ( 'community_post_reports.id', 'DESC' )->findAll ();
    return $this->sendResponse ( true, 'Reports retrieved', $reports );
  }

  public function adminDismissReport()
  {
    $id = $this->request->getJsonVar ( 'id' ) ?? $this->request->getVar ( 'id' );
    $this->reportModel->delete ( $id );
    return $this->sendResponse ( true, 'Report dismissed' );
  }

  public function adminDismissAllReports()
  {
    $postId = $this->request->getJsonVar ( 'post_id' ) ?? $this->request->getVar ( 'post_id' );
    $this->reportModel->where ( 'post_id', $postId )->delete ();
    return $this->sendResponse ( true, 'All reports for this post dismissed' );
  }

  public function adminAddReply()
  {
    $parentId    = $this->request->getJsonVar ( 'parent_comment_id' );
    $commentText = $this->request->getJsonVar ( 'comment' );
    $adminId     = $this->request->getJsonVar ( 'admin_id' ) ?? 1;

    $parentComment = $this->commentModel->find ( $parentId );
    if ( !$parentComment ) return $this->sendResponse ( false, 'Parent comment not found' );

    $this->db->transStart ();
    try {
      $data = [
        'post_id'           => $parentComment['post_id'],
        'user_id'           => $adminId,
        'user_type'         => 'admin',
        'comment'           => $commentText,
        'parent_comment_id' => $parentId,
        'status'            => 'active',
        'created_at'        => date ( 'Y-m-d H:i:s' )
      ];

      $this->commentModel->insert ( $data );
      $this->postModel->set ( 'comments_count', 'comments_count + 1', false )->where ( 'id', $parentComment['post_id'] )->update ();

      // Notify user
      if ( $parentComment['user_type'] != 'admin' && !empty ( $parentComment['user_id'] ) ) {
        $token = $this->getUserDeviceToken ( $parentComment['user_id'] );
        if ( $token ) {
          $this->pushNotify->notify (
            $token,
            "Petsfolio response",
            "Petsfolio just replied to your comment.",
            'PostDetailScreen',
            ['postId' => $parentComment['post_id']]
          );
        }
      }

      $this->db->transComplete ();
      return $this->sendResponse ( true, 'Reply added successfully' );
    } catch (Exception $e) {
      $this->db->transRollback ();
      return $this->sendResponse ( false, $e->getMessage () );
    }
  }

  public function adminDeleteComment()
  {
    $id      = $this->request->getJsonVar ( 'id' ) ?? $this->request->getVar ( 'id' );
    $comment = $this->commentModel->find ( $id );
    if ( !$comment ) return $this->sendResponse ( false, 'Comment not found' );

    $this->db->transStart ();
    try {
      $this->commentModel->delete ( $id );
      $post = $this->postModel->find ( $comment['post_id'] );
      if ( $post ) {
        $this->postModel->update ( $comment['post_id'], ['comments_count' => max ( 0, (int) $post['comments_count'] - 1 )] );
      }
      $this->db->transComplete ();
      return $this->sendResponse ( true, 'Comment removed' );
    } catch (Exception $e) {
      $this->db->transRollback ();
      return $this->sendResponse ( false, $e->getMessage () );
    }
  }

  public function adminProcessScheduledPosts()
  {
    $now            = date ( 'Y-m-d H:i:s' );
    $scheduledPosts = $this->postModel
      ->where ( 'status', 'scheduled' )
      ->where ( 'scheduled_time <=', $now )
      ->findAll ();

    $count = 0;
    foreach ( $scheduledPosts as $post ) {
      $this->postModel->update ( $post['id'], [
        'status'     => 'active',
        'created_at' => $now
      ] );

      if ( $post['user_type'] == 'admin' ) {
        $channel = $this->channelModel->find ( $post['channel_id'] );
        $this->notifyChannelMembers (
          $post['channel_id'],
          $post['id'],
          "A new announcement has been posted in #{$channel['name']}.",
          "Important Update"
        );
      }
      $count++;
    }

    return $this->sendResponse ( true, 'Processed ' . $count . ' scheduled posts' );
  }

  private function notifyChannelMembers( $channelId, $postId, $message, $title = "New Post" )
  {
    try {
      $members = $this->channelJoinModel->where ( 'channel_id', $channelId )->findAll ();
      if ( !empty ( $members ) ) {
        $memberIds = array_column ( $members, 'user_id' );
        $tokens    = $this->db->table ( 'users' )
          ->select ( 'device_token' )
          ->whereIn ( 'id', $memberIds )
          ->where ( 'device_token !=', '' )
          ->get ()->getResultArray ();

        $fcmTokens = array_column ( $tokens, 'device_token' );
        if ( !empty ( $fcmTokens ) ) {
          $this->pushNotify->sendMulticast (
            $fcmTokens,
            $title,
            $message,
            'PostDetailScreen',
            ['postId' => $postId]
          );
        }
      }
    } catch (Exception $e) {
      log_message ( 'error', 'Push Notify Error (Admin Channel Notify): ' . $e->getMessage () );
    }
  }

}
