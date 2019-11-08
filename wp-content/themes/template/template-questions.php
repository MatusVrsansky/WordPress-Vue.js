<?php
/**
 * Template Name: Vue Games
 * @package WordPress
 * @since Twenty Fourteen 1.0
 */

$context = Timber\Timber::get_context();
$context['post'] = new Timber\Post();

Timber\Timber::render('page/questions.twig', $context);

?>
