<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get ( '/', 'Home::index' );


$routes->group ( 'community', ['namespace' => 'App\Controllers\Api'], function ( $routes )
{

  $routes->controller ( 'CommunityController', function ( $routes )
  {

    $routes->post ( 'createPost' );
    $routes->post ( 'updatePost' );
    $routes->post ( 'deletePost' );

    $routes->get ( 'getAllPosts' );
    $routes->get ( 'getTrendingFeed' );
    $routes->get ( 'getPostByChannel' );
    $routes->get ( 'getAllChannels' );

    $routes->post ( 'joinChannel' );

    $routes->post ( 'LikePost' );
    $routes->post ( 'DislikePost' );

    $routes->post ( 'addComment' );
    $routes->post ( 'replyToComment', 'addComment' );

    $routes->post ( 'deleteComment' );
    $routes->post ( 'likeAndDislikeForAComment' );
    $routes->post ( 'DislikeComment' );

    $routes->get ( 'getMyPosts' );
    $routes->get ( 'getMyComments' );
    $routes->get ( 'getMySavedPosts' );

    $routes->post ( 'saveMyPost' );

    $routes->post ( 'ReportPost' );
    $routes->post ( 'ReportComment' );

    $routes->get ( 'getPostById' );

    $routes->post ( 'deleteMedia' );

    $routes->get ( 'search' );

    // Admin APIs
    $routes->get ( 'adminGetChannels' );
    $routes->post ( 'adminAddChannel' );
    $routes->post ( 'adminUpdateChannel' );
    $routes->post ( 'adminDeleteChannel' );
    $routes->get ( 'adminGetPosts' );
    $routes->post ( 'adminAddPost' );
    $routes->post ( 'adminApprovePost' );
    $routes->post ( 'adminTogglePin' );
    $routes->post ( 'adminDeletePost' );
    $routes->post ( 'adminBlockPost' );
    $routes->post ( 'adminUnblockPost' );
    $routes->get ( 'adminGetReports' );
    $routes->post ( 'adminDismissReport' );
    $routes->post ( 'adminDismissAllReports' );
    $routes->post ( 'adminAddReply' );
    $routes->post ( 'adminDeleteComment' );
    $routes->get ( 'adminProcessScheduledPosts' );
    $routes->get ( 'adminGetDashboardStats' );

  } );
} );

$routes->group ( 'auth', ['namespace' => 'App\Controllers\Api'], function ( $routes )
{
  $routes->post ( 'login', 'AuthController::login' );
  $routes->post ( 'adminLogin', 'AuthController::adminLogin' );
  $routes->get ( 'getProfile', 'AuthController::getProfile' );
  $routes->post ( 'updateProfile', 'AuthController::updateProfile' );
} );