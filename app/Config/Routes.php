<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get ( '/', 'Home::index' );


$routes->group ( 'api/community', ['namespace' => 'App\Controllers\Api'], function ( $routes )
{
  $routes->post ( 'createPost', 'CommunityController::createPost' );
  $routes->post ( 'updatePost', 'CommunityController::updatePost' );
  $routes->post ( 'deletePost', 'CommunityController::deletePost' );

  $routes->get ( 'getAllPosts', 'CommunityController::getAllPosts' );
  $routes->get ( 'getTrendingFeed', 'CommunityController::getTrendingFeed' );
  $routes->get ( 'getPostByChannel', 'CommunityController::getPostByChannel' );
  $routes->get ( 'getAllChannels', 'CommunityController::getAllChannels' );

  $routes->post ( 'joinChannel', 'CommunityController::joinChannel' );

  $routes->post ( 'LikePost', 'CommunityController::LikePost' );
  $routes->post ( 'DislikePost', 'CommunityController::DislikePost' );

  $routes->post ( 'addComment', 'CommunityController::addComment' );
  $routes->post ( 'replyToComment', 'CommunityController::addComment' );

  $routes->post ( 'deleteComment', 'CommunityController::deleteComment' );
  $routes->post ( 'likeAndDislikeForAComment', 'CommunityController::likeAndDislikeForAComment' );
  $routes->post ( 'DislikeComment', 'CommunityController::DislikeComment' );

  $routes->get ( 'getMyPosts', 'CommunityController::getMyPosts' );
  $routes->get ( 'getMyComments', 'CommunityController::getMyComments' );
  $routes->get ( 'getMySavedPosts', 'CommunityController::getMySavedPosts' );

  $routes->post ( 'saveMyPost', 'CommunityController::saveMyPost' );

  $routes->post ( 'ReportPost', 'CommunityController::ReportPost' );
  $routes->post ( 'ReportComment', 'CommunityController::ReportComment' );

  $routes->get ( 'getPostById', 'CommunityController::getPostById' );

  $routes->post ( 'deleteMedia', 'CommunityController::deleteMedia' );

  $routes->get ( 'search', 'CommunityController::search' );

  // Admin APIs
  $routes->get ( 'adminGetChannels', 'CommunityController::adminGetChannels' );
  $routes->post ( 'adminAddChannel', 'CommunityController::adminAddChannel' );
  $routes->post ( 'adminUpdateChannel', 'CommunityController::adminUpdateChannel' );
  $routes->post ( 'adminDeleteChannel', 'CommunityController::adminDeleteChannel' );
  $routes->get ( 'adminGetPosts', 'CommunityController::adminGetPosts' );
  $routes->post ( 'adminAddPost', 'CommunityController::adminAddPost' );
  $routes->post ( 'adminApprovePost', 'CommunityController::adminApprovePost' );
  $routes->post ( 'adminTogglePin', 'CommunityController::adminTogglePin' );
  $routes->post ( 'adminDeletePost', 'CommunityController::adminDeletePost' );
  $routes->post ( 'adminBlockPost', 'CommunityController::adminBlockPost' );
  $routes->post ( 'adminUnblockPost', 'CommunityController::adminUnblockPost' );
  $routes->get ( 'adminGetReports', 'CommunityController::adminGetReports' );
  $routes->post ( 'adminDismissReport', 'CommunityController::adminDismissReport' );
  $routes->post ( 'adminDismissAllReports', 'CommunityController::adminDismissAllReports' );
  $routes->post ( 'adminAddReply', 'CommunityController::adminAddReply' );
  $routes->post ( 'adminDeleteComment', 'CommunityController::adminDeleteComment' );
  $routes->get ( 'adminProcessScheduledPosts', 'CommunityController::adminProcessScheduledPosts' );
  $routes->get ( 'adminGetDashboardStats', 'CommunityController::adminGetDashboardStats' );
} );

$routes->group ( 'auth', ['namespace' => 'App\Controllers\Api'], function ( $routes )
{
  $routes->post ( 'login', 'AuthController::login' );
  $routes->post ( 'adminLogin', 'AuthController::adminLogin' );
  $routes->get ( 'getProfile', 'AuthController::getProfile' );
  $routes->post ( 'updateProfile', 'AuthController::updateProfile' );
} );